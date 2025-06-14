import React, { useState, useEffect } from 'react';
import { Card, Table, Button, Alert, Spinner, Badge } from 'react-bootstrap';
import { FaPlay, FaSync, FaCalendarAlt } from 'react-icons/fa';
import axios from 'axios';

const SchedulerManagement = ({ lastRefresh }) => {
  const [schedulerData, setSchedulerData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [actionLoading, setActionLoading] = useState(false);
  const [actionStatus, setActionStatus] = useState(null);
  const [runningJob, setRunningJob] = useState(null);

  const fetchSchedulerStatus = async () => {
    setLoading(true);
    try {
      const response = await axios.get('/api/admin/scheduler/status');
      setSchedulerData(response.data);
      setError(null);
    } catch (err) {
      console.error('Error fetching scheduler status:', err);
      setError('Gagal mengambil data scheduler');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchSchedulerStatus();
  }, [lastRefresh]);

  const handleRunJob = async (jobName) => {
    setActionLoading(true);
    setActionStatus(null);
    setRunningJob(jobName);
    
    try {
      const response = await axios.post(`/api/admin/scheduler/run-job/${jobName}`);
      setActionStatus({
        type: 'success',
        message: `Job "${jobName}" berhasil dijadwalkan. Status: ${response.data.status}`
      });
      
      // Refresh scheduler status after a delay
      setTimeout(() => fetchSchedulerStatus(), 2000);
    } catch (err) {
      console.error(`Error running job ${jobName}:`, err);
      setActionStatus({
        type: 'danger',
        message: `Gagal menjalankan job "${jobName}": ${err.response?.data?.detail || err.message}`
      });
    } finally {
      setActionLoading(false);
      setRunningJob(null);
    }
  };

  const getTimeFromNow = (dateTimeStr) => {
    if (!dateTimeStr) return 'Never';
    
    const date = new Date(dateTimeStr);
    const now = new Date();
    
    // Calculate difference in milliseconds
    const diffMs = date - now;
    
    // If the date is in the past
    if (diffMs < 0) {
      return 'Overdue';
    }
    
    // Calculate difference in minutes, hours, days
    const diffMins = Math.floor(diffMs / (1000 * 60));
    const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
    const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));
    
    if (diffDays > 0) {
      return `in ${diffDays} day${diffDays > 1 ? 's' : ''}`;
    }
    if (diffHours > 0) {
      return `in ${diffHours} hour${diffHours > 1 ? 's' : ''}`;
    }
    if (diffMins > 0) {
      return `in ${diffMins} minute${diffMins > 1 ? 's' : ''}`;
    }
    
    return 'Less than a minute';
  };

  const getTimeSince = (dateTimeStr) => {
    if (!dateTimeStr) return 'Never';
    
    const date = new Date(dateTimeStr);
    const now = new Date();
    
    // Calculate difference in milliseconds
    const diffMs = now - date;
    
    // Calculate difference in minutes, hours, days
    const diffMins = Math.floor(diffMs / (1000 * 60));
    const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
    const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));
    
    if (diffDays > 0) {
      return `${diffDays} day${diffDays > 1 ? 's' : ''} ago`;
    }
    if (diffHours > 0) {
      return `${diffHours} hour${diffHours > 1 ? 's' : ''} ago`;
    }
    if (diffMins > 0) {
      return `${diffMins} minute${diffMins > 1 ? 's' : ''} ago`;
    }
    
    return 'Just now';
  };

  if (loading) {
    return (
      <div className="text-center py-5">
        <Spinner animation="border" variant="primary" />
        <p className="mt-3">Loading scheduler data...</p>
      </div>
    );
  }

  if (error) {
    return (
      <Alert variant="danger">
        {error}
      </Alert>
    );
  }

  return (
    <div className="scheduler-management">
      <h3 className="mb-4">Job Scheduler</h3>
      
      <Card className="mb-4">
        <Card.Header className="d-flex justify-content-between align-items-center">
          <h5 className="mb-0">
            <FaCalendarAlt className="me-2" /> 
            Scheduler Status: 
            <Badge bg="success" className="ms-2">
              {schedulerData?.status || 'Unknown'}
            </Badge>
          </h5>
          <Button 
            variant="light" 
            size="sm" 
            onClick={fetchSchedulerStatus}
            disabled={loading}
          >
            <FaSync />
          </Button>
        </Card.Header>
        <Card.Body>
          {actionStatus && (
            <Alert 
              variant={actionStatus.type} 
              className="mb-4" 
              onClose={() => setActionStatus(null)} 
              dismissible
            >
              {actionStatus.message}
            </Alert>
          )}
          
          <div className="table-wrapper">
            <Table className="admin-table" hover responsive>
              <thead>
                <tr>
                  <th>Job Name</th>
                  <th>Last Run</th>
                  <th>Next Run</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                {schedulerData?.jobs?.length > 0 ? (
                  schedulerData.jobs.map((job, index) => (
                    <tr key={index}>
                      <td>{job.name}</td>
                      <td>{job.last_run ? getTimeSince(job.last_run) : 'Never'}</td>
                      <td>{job.next_run ? getTimeFromNow(job.next_run) : 'Not scheduled'}</td>
                      <td>
                        {job.last_run ? (
                          <Badge bg="success">Completed</Badge>
                        ) : (
                          <Badge bg="secondary">Pending</Badge>
                        )}
                      </td>
                      <td>
                        <Button 
                          variant="primary" 
                          size="sm" 
                          onClick={() => handleRunJob(job.name)}
                          disabled={actionLoading && runningJob === job.name}
                        >
                          {actionLoading && runningJob === job.name ? (
                            <>
                              <Spinner as="span" animation="border" size="sm" className="me-1" />
                              Running...
                            </>
                          ) : (
                            <>
                              <FaPlay className="me-1" /> Run Now
                            </>
                          )}
                        </Button>
                      </td>
                    </tr>
                  ))
                ) : (
                  <tr>
                    <td colSpan="5" className="text-center">No scheduled jobs found</td>
                  </tr>
                )}
              </tbody>
            </Table>
          </div>

          <div className="mt-4">
            <h6>Available Jobs:</h6>
            <ul>
              <li><strong>update_stocks</strong>: Refresh stock data from external sources</li>
              <li><strong>clear_cache</strong>: Clear expired cache entries</li>
              <li><strong>optimize_db</strong>: Run database optimization routines</li>
            </ul>
          </div>
        </Card.Body>
      </Card>
      
      {schedulerData?.cache_stats && (
        <Card className="mb-4">
          <Card.Header>
            <h5 className="mb-0">Cache Information</h5>
          </Card.Header>
          <Card.Body>
            <Table className="admin-table" bordered size="sm">
              <tbody>
                <tr>
                  <th>Cache Entries</th>
                  <td>{schedulerData.cache_stats.total_entries || 0}</td>
                </tr>
                <tr>
                  <th>Memory Usage</th>
                  <td>{schedulerData.cache_stats.memory_usage || 'Unknown'}</td>
                </tr>
                <tr>
                  <th>Hit Rate</th>
                  <td>{schedulerData.cache_stats.hit_rate ? `${schedulerData.cache_stats.hit_rate}%` : 'N/A'}</td>
                </tr>
                <tr>
                  <th>Expired Entries</th>
                  <td>{schedulerData.cache_stats.expired_entries || 0}</td>
                </tr>
              </tbody>
            </Table>
          </Card.Body>
        </Card>
      )}
      
      <p className="text-muted mt-3">
        <small>Last updated: {schedulerData?.timestamp || 'N/A'}</small>
      </p>
    </div>
  );
};

export default SchedulerManagement;

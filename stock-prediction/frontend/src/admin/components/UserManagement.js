import React, { useState, useEffect } from 'react';
import { Card, Table, Button, Alert, Spinner, Form, Row, Col, Modal, Badge } from 'react-bootstrap';
import { FaUserPlus, FaUserEdit, FaUserTimes, FaSearch, FaSync } from 'react-icons/fa';
import axios from 'axios';

const UserManagement = ({ lastRefresh }) => {
  const [users, setUsers] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [searchTerm, setSearchTerm] = useState('');
  const [showModal, setShowModal] = useState(false);
  const [modalMode, setModalMode] = useState('add'); // 'add' or 'edit'
  const [currentUser, setCurrentUser] = useState(null);
  
  const [formData, setFormData] = useState({
    username: '',
    email: '',
    full_name: '',
    is_active: true,
    is_superuser: false,
    password: '',
    confirm_password: ''
  });
  
  const [formErrors, setFormErrors] = useState({});
  const [formSubmitting, setFormSubmitting] = useState(false);

  // Fetch users data
  const fetchUsers = async () => {
    setLoading(true);
    setError(null);
    
    try {
      // In a real implementation, this would connect to your user API endpoint
      // For demo purposes, we'll simulate data
      
      // This would be replaced with actual API call:
      // const response = await axios.get('/api/admin/users');
      // setUsers(response.data);
      
      // Simulated user data
      setTimeout(() => {
        const mockUsers = [
          { 
            id: 1, 
            username: 'admin', 
            email: 'admin@zahaam.com', 
            full_name: 'Administrator', 
            is_active: true, 
            is_superuser: true, 
            created_at: '2025-05-01T10:00:00',
            last_login: '2025-06-12T05:10:22'
          },
          { 
            id: 2, 
            username: 'analyst1', 
            email: 'analyst@zahaam.com', 
            full_name: 'Finance Analyst', 
            is_active: true, 
            is_superuser: false,
            created_at: '2025-05-15T14:30:00',
            last_login: '2025-06-11T16:45:12'
          },
          { 
            id: 3, 
            username: 'trader1', 
            email: 'trader@zahaam.com', 
            full_name: 'Stock Trader', 
            is_active: true, 
            is_superuser: false,
            created_at: '2025-06-01T09:15:00',
            last_login: '2025-06-10T11:20:35'
          },
          { 
            id: 4, 
            username: 'inactive_user', 
            email: 'inactive@zahaam.com', 
            full_name: 'Inactive User', 
            is_active: false, 
            is_superuser: false,
            created_at: '2025-05-20T16:40:00',
            last_login: '2025-05-25T08:15:42'
          }
        ];
        
        setUsers(mockUsers);
        setLoading(false);
      }, 800);
      
    } catch (err) {
      console.error('Error fetching users:', err);
      setError('Gagal mengambil data pengguna');
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchUsers();
  }, [lastRefresh]);

  // Reset form data
  const resetForm = () => {
    setFormData({
      username: '',
      email: '',
      full_name: '',
      is_active: true,
      is_superuser: false,
      password: '',
      confirm_password: ''
    });
    setFormErrors({});
  };

  // Open modal for adding a new user
  const handleAddUser = () => {
    resetForm();
    setModalMode('add');
    setShowModal(true);
  };

  // Open modal for editing an existing user
  const handleEditUser = (user) => {
    setCurrentUser(user);
    setFormData({
      username: user.username,
      email: user.email,
      full_name: user.full_name,
      is_active: user.is_active,
      is_superuser: user.is_superuser,
      password: '',
      confirm_password: ''
    });
    setModalMode('edit');
    setShowModal(true);
  };

  // Handle form submission
  const handleSubmit = async (e) => {
    e.preventDefault();
    setFormSubmitting(true);
    setFormErrors({});
    
    // Form validation
    const errors = {};
    if (!formData.username) errors.username = 'Username wajib diisi';
    if (!formData.email) errors.email = 'Email wajib diisi';
    if (!formData.full_name) errors.full_name = 'Nama lengkap wajib diisi';
    
    // Additional validation for new users or password changes
    if (modalMode === 'add' || (modalMode === 'edit' && formData.password)) {
      if (!formData.password) errors.password = 'Password wajib diisi';
      else if (formData.password.length < 8) errors.password = 'Password minimal 8 karakter';
      if (formData.password !== formData.confirm_password) errors.confirm_password = 'Password tidak cocok';
    }
    
    if (Object.keys(errors).length > 0) {
      setFormErrors(errors);
      setFormSubmitting(false);
      return;
    }
    
    try {
      if (modalMode === 'add') {
        // This would be replaced with actual API call:
        // await axios.post('/api/admin/users', formData);
        
        // Simulate successful API call
        setTimeout(() => {
          // Add new user to the list (with a generated ID)
          const newUser = {
            ...formData,
            id: Math.max(...users.map(u => u.id)) + 1,
            created_at: new Date().toISOString(),
            last_login: null
          };
          
          setUsers([...users, newUser]);
          setShowModal(false);
          setFormSubmitting(false);
        }, 1000);
      } else {
        // This would be replaced with actual API call:
        // await axios.put(`/api/admin/users/${currentUser.id}`, formData);
        
        // Simulate successful API call
        setTimeout(() => {
          // Update user in the list
          const updatedUsers = users.map(user => 
            user.id === currentUser.id ? { ...user, ...formData } : user
          );
          
          setUsers(updatedUsers);
          setShowModal(false);
          setFormSubmitting(false);
        }, 1000);
      }
    } catch (err) {
      console.error('Error saving user:', err);
      setFormErrors({ submit: 'Gagal menyimpan data pengguna' });
      setFormSubmitting(false);
    }
  };

  // Handle toggling user status (active/inactive)
  const handleToggleUserStatus = async (user) => {
    try {
      // This would be replaced with actual API call:
      // await axios.patch(`/api/admin/users/${user.id}/toggle-status`);
      
      // Simulate successful API call
      // Update user in the list
      const updatedUsers = users.map(u => 
        u.id === user.id ? { ...u, is_active: !u.is_active } : u
      );
      
      setUsers(updatedUsers);
    } catch (err) {
      console.error('Error toggling user status:', err);
      setError('Gagal mengubah status pengguna');
    }
  };

  // Filter users by search term
  const filteredUsers = users.filter(user => 
    user.username.toLowerCase().includes(searchTerm.toLowerCase()) ||
    user.email.toLowerCase().includes(searchTerm.toLowerCase()) ||
    user.full_name.toLowerCase().includes(searchTerm.toLowerCase())
  );

  return (
    <div className="user-management">
      <h3 className="mb-4">User Management</h3>
      
      <Card className="mb-4">
        <Card.Header className="d-flex justify-content-between align-items-center">
          <div className="d-flex align-items-center">
            <h5 className="mb-0">Users</h5>
            <Badge bg="primary" className="ms-2">{users.length}</Badge>
          </div>
          <div className="d-flex">
            <Form.Group className="mb-0 me-2">
              <Form.Control
                type="text"
                placeholder="Search users..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                size="sm"
              />
            </Form.Group>
            <Button 
              variant="light" 
              size="sm" 
              onClick={fetchUsers}
              disabled={loading}
              className="me-2"
            >
              <FaSync />
            </Button>
            <Button 
              variant="primary" 
              size="sm"
              onClick={handleAddUser}
            >
              <FaUserPlus className="me-1" /> Add User
            </Button>
          </div>
        </Card.Header>
        <Card.Body>
          {error && (
            <Alert variant="danger" onClose={() => setError(null)} dismissible>
              {error}
            </Alert>
          )}
          
          {loading ? (
            <div className="text-center py-4">
              <Spinner animation="border" variant="primary" />
              <p className="mt-3">Loading users data...</p>
            </div>
          ) : (
            <div className="table-wrapper">
              <Table className="admin-table" hover responsive>
                <thead>
                  <tr>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Last Login</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  {filteredUsers.length > 0 ? (
                    filteredUsers.map(user => (
                      <tr key={user.id}>
                        <td>{user.username}</td>
                        <td>{user.full_name}</td>
                        <td>{user.email}</td>
                        <td>
                          {user.is_superuser ? (
                            <Badge bg="danger">Admin</Badge>
                          ) : (
                            <Badge bg="info">User</Badge>
                          )}
                        </td>
                        <td>
                          {user.is_active ? (
                            <Badge bg="success">Active</Badge>
                          ) : (
                            <Badge bg="secondary">Inactive</Badge>
                          )}
                        </td>
                        <td>{user.last_login ? new Date(user.last_login).toLocaleString() : 'Never'}</td>
                        <td>
                          <Button 
                            variant="outline-primary" 
                            size="sm"
                            className="me-1"
                            onClick={() => handleEditUser(user)}
                          >
                            <FaUserEdit />
                          </Button>
                          <Button 
                            variant={user.is_active ? "outline-danger" : "outline-success"} 
                            size="sm"
                            onClick={() => handleToggleUserStatus(user)}
                          >
                            {user.is_active ? <FaUserTimes /> : <FaUserPlus />}
                          </Button>
                        </td>
                      </tr>
                    ))
                  ) : (
                    <tr>
                      <td colSpan="7" className="text-center">No users found</td>
                    </tr>
                  )}
                </tbody>
              </Table>
            </div>
          )}
        </Card.Body>
      </Card>
      
      {/* User Modal Form */}
      <Modal show={showModal} onHide={() => setShowModal(false)} size="lg">
        <Modal.Header closeButton>
          <Modal.Title>
            {modalMode === 'add' ? 'Add New User' : 'Edit User'}
          </Modal.Title>
        </Modal.Header>
        <Form onSubmit={handleSubmit}>
          <Modal.Body>
            {formErrors.submit && (
              <Alert variant="danger">{formErrors.submit}</Alert>
            )}
            
            <Row className="mb-3">
              <Col md={6}>
                <Form.Group>
                  <Form.Label>Username</Form.Label>
                  <Form.Control
                    type="text"
                    value={formData.username}
                    onChange={e => setFormData({...formData, username: e.target.value})}
                    isInvalid={!!formErrors.username}
                    readOnly={modalMode === 'edit'} // Username shouldn't be changed after creation
                  />
                  <Form.Control.Feedback type="invalid">
                    {formErrors.username}
                  </Form.Control.Feedback>
                </Form.Group>
              </Col>
              <Col md={6}>
                <Form.Group>
                  <Form.Label>Email</Form.Label>
                  <Form.Control
                    type="email"
                    value={formData.email}
                    onChange={e => setFormData({...formData, email: e.target.value})}
                    isInvalid={!!formErrors.email}
                  />
                  <Form.Control.Feedback type="invalid">
                    {formErrors.email}
                  </Form.Control.Feedback>
                </Form.Group>
              </Col>
            </Row>
            
            <Row className="mb-3">
              <Col>
                <Form.Group>
                  <Form.Label>Full Name</Form.Label>
                  <Form.Control
                    type="text"
                    value={formData.full_name}
                    onChange={e => setFormData({...formData, full_name: e.target.value})}
                    isInvalid={!!formErrors.full_name}
                  />
                  <Form.Control.Feedback type="invalid">
                    {formErrors.full_name}
                  </Form.Control.Feedback>
                </Form.Group>
              </Col>
            </Row>
            
            <Row className="mb-3">
              <Col md={6}>
                <Form.Group>
                  <Form.Label>Password {modalMode === 'edit' && '(leave blank to keep current)'}</Form.Label>
                  <Form.Control
                    type="password"
                    value={formData.password}
                    onChange={e => setFormData({...formData, password: e.target.value})}
                    isInvalid={!!formErrors.password}
                  />
                  <Form.Control.Feedback type="invalid">
                    {formErrors.password}
                  </Form.Control.Feedback>
                </Form.Group>
              </Col>
              <Col md={6}>
                <Form.Group>
                  <Form.Label>Confirm Password</Form.Label>
                  <Form.Control
                    type="password"
                    value={formData.confirm_password}
                    onChange={e => setFormData({...formData, confirm_password: e.target.value})}
                    isInvalid={!!formErrors.confirm_password}
                    disabled={!formData.password}
                  />
                  <Form.Control.Feedback type="invalid">
                    {formErrors.confirm_password}
                  </Form.Control.Feedback>
                </Form.Group>
              </Col>
            </Row>
            
            <Row className="mb-3">
              <Col md={6}>
                <Form.Group>
                  <div className="mb-2">Status</div>
                  <Form.Check
                    type="switch"
                    id="user-active"
                    label="User Active"
                    checked={formData.is_active}
                    onChange={e => setFormData({...formData, is_active: e.target.checked})}
                  />
                </Form.Group>
              </Col>
              <Col md={6}>
                <Form.Group>
                  <div className="mb-2">Role</div>
                  <Form.Check
                    type="switch"
                    id="user-admin"
                    label="Administrator"
                    checked={formData.is_superuser}
                    onChange={e => setFormData({...formData, is_superuser: e.target.checked})}
                  />
                  <Form.Text className="text-muted">
                    Administrators have full access to all features including the admin panel.
                  </Form.Text>
                </Form.Group>
              </Col>
            </Row>
          </Modal.Body>
          <Modal.Footer>
            <Button variant="secondary" onClick={() => setShowModal(false)}>
              Cancel
            </Button>
            <Button 
              variant="primary" 
              type="submit" 
              disabled={formSubmitting}
            >
              {formSubmitting ? (
                <>
                  <Spinner as="span" animation="border" size="sm" className="me-2" />
                  Saving...
                </>
              ) : (
                'Save User'
              )}
            </Button>
          </Modal.Footer>
        </Form>
      </Modal>
    </div>
  );
};

export default UserManagement;

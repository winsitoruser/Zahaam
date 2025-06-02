import React, { useState, useEffect } from 'react';
import { Table, Form, InputGroup, Button, Pagination, Dropdown } from 'react-bootstrap';
import { Link } from 'react-router-dom';

const StockTable = ({ stocks }) => {
  const [filteredStocks, setFilteredStocks] = useState([]);
  const [searchTerm, setSearchTerm] = useState('');
  const [currentPage, setCurrentPage] = useState(1);
  const [itemsPerPage, setItemsPerPage] = useState(10);
  const [sortField, setSortField] = useState('ticker');
  const [sortDirection, setSortDirection] = useState('asc');
  
  useEffect(() => {
    // Filter stocks based on search term
    const filtered = stocks.filter(stock => 
      stock.ticker.toLowerCase().includes(searchTerm.toLowerCase()) ||
      stock.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
      stock.sector.toLowerCase().includes(searchTerm.toLowerCase())
    );
    
    // Sort the filtered stocks
    const sorted = [...filtered].sort((a, b) => {
      if (a[sortField] < b[sortField]) return sortDirection === 'asc' ? -1 : 1;
      if (a[sortField] > b[sortField]) return sortDirection === 'asc' ? 1 : -1;
      return 0;
    });
    
    setFilteredStocks(sorted);
  }, [stocks, searchTerm, sortField, sortDirection]);
  
  // Get current items for pagination
  const indexOfLastItem = currentPage * itemsPerPage;
  const indexOfFirstItem = indexOfLastItem - itemsPerPage;
  const currentItems = filteredStocks.slice(indexOfFirstItem, indexOfLastItem);
  
  // Change page
  const paginate = (pageNumber) => setCurrentPage(pageNumber);
  
  // Handle sorting
  const handleSort = (field) => {
    if (sortField === field) {
      setSortDirection(sortDirection === 'asc' ? 'desc' : 'asc');
    } else {
      setSortField(field);
      setSortDirection('asc');
    }
  };
  
  // Generate pagination items
  const totalPages = Math.ceil(filteredStocks.length / itemsPerPage);
  let paginationItems = [];
  
  if (totalPages <= 7) {
    // Less than 7 pages, show all
    for (let i = 1; i <= totalPages; i++) {
      paginationItems.push(
        <Pagination.Item 
          key={i} 
          active={i === currentPage}
          onClick={() => paginate(i)}
        >
          {i}
        </Pagination.Item>
      );
    }
  } else {
    // More than 7 pages, show with ellipsis
    paginationItems.push(
      <Pagination.Item 
        key={1} 
        active={1 === currentPage}
        onClick={() => paginate(1)}
      >
        1
      </Pagination.Item>
    );
    
    if (currentPage > 3) {
      paginationItems.push(<Pagination.Ellipsis key="ellipsis1" />);
    }
    
    // Show current page and neighbors
    const startPage = Math.max(2, currentPage - 1);
    const endPage = Math.min(totalPages - 1, currentPage + 1);
    
    for (let i = startPage; i <= endPage; i++) {
      paginationItems.push(
        <Pagination.Item 
          key={i} 
          active={i === currentPage}
          onClick={() => paginate(i)}
        >
          {i}
        </Pagination.Item>
      );
    }
    
    if (currentPage < totalPages - 2) {
      paginationItems.push(<Pagination.Ellipsis key="ellipsis2" />);
    }
    
    paginationItems.push(
      <Pagination.Item 
        key={totalPages} 
        active={totalPages === currentPage}
        onClick={() => paginate(totalPages)}
      >
        {totalPages}
      </Pagination.Item>
    );
  }
  
  return (
    <div className="stock-table-container">
      <div className="d-flex justify-content-between align-items-center mb-3">
        <div>
          <InputGroup>
            <Form.Control
              placeholder="Cari saham (kode, nama, sektor)..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
            />
            <Button variant="outline-secondary">
              <i className="bi bi-search"></i>
            </Button>
          </InputGroup>
        </div>
        <div className="d-flex align-items-center">
          <span className="me-2">Tampilkan:</span>
          <Dropdown>
            <Dropdown.Toggle variant="outline-secondary" id="dropdown-items-per-page">
              {itemsPerPage}
            </Dropdown.Toggle>
            <Dropdown.Menu>
              {[5, 10, 25, 50, 100].map(value => (
                <Dropdown.Item 
                  key={value} 
                  onClick={() => {
                    setItemsPerPage(value);
                    setCurrentPage(1);
                  }}
                  active={value === itemsPerPage}
                >
                  {value}
                </Dropdown.Item>
              ))}
            </Dropdown.Menu>
          </Dropdown>
        </div>
      </div>
      
      <div className="table-responsive">
        <Table hover bordered className="mb-0">
          <thead className="table-light">
            <tr>
              <th 
                onClick={() => handleSort('ticker')}
                className="sortable-header"
              >
                Kode 
                {sortField === 'ticker' && (
                  <i className={`bi bi-sort-${sortDirection === 'asc' ? 'up' : 'down'} ms-1`}></i>
                )}
              </th>
              <th 
                onClick={() => handleSort('name')}
                className="sortable-header"
              >
                Nama Perusahaan
                {sortField === 'name' && (
                  <i className={`bi bi-sort-${sortDirection === 'asc' ? 'up' : 'down'} ms-1`}></i>
                )}
              </th>
              <th 
                onClick={() => handleSort('price')}
                className="sortable-header"
              >
                Harga (Rp)
                {sortField === 'price' && (
                  <i className={`bi bi-sort-${sortDirection === 'asc' ? 'up' : 'down'} ms-1`}></i>
                )}
              </th>
              <th 
                onClick={() => handleSort('change')}
                className="sortable-header"
              >
                Perubahan (%)
                {sortField === 'change' && (
                  <i className={`bi bi-sort-${sortDirection === 'asc' ? 'up' : 'down'} ms-1`}></i>
                )}
              </th>
              <th 
                onClick={() => handleSort('sector')}
                className="sortable-header"
              >
                Sektor
                {sortField === 'sector' && (
                  <i className={`bi bi-sort-${sortDirection === 'asc' ? 'up' : 'down'} ms-1`}></i>
                )}
              </th>
              <th className="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            {currentItems.length > 0 ? (
              currentItems.map((stock, index) => (
                <tr key={stock.ticker}>
                  <td><strong>{stock.ticker}</strong></td>
                  <td>{stock.name}</td>
                  <td>{stock.price ? stock.price.toLocaleString('id-ID') : 0}</td>
                  <td>
                    <span className={`badge ${stock.change >= 0 ? 'bg-success' : 'bg-danger'}`}>
                      {stock.change >= 0 ? '+' : ''}{stock.change ? stock.change.toFixed(2) : '0.00'}%
                    </span>
                  </td>
                  <td>{stock.sector}</td>
                  <td className="text-center">
                    <Link 
                      to={`/stocks/${stock.ticker}`} 
                      className="btn btn-sm btn-primary me-1"
                    >
                      <i className="bi bi-graph-up"></i> Detail
                    </Link>
                    <Button 
                      variant="outline-secondary" 
                      size="sm"
                      onClick={() => alert(`Menambahkan ${stock.ticker} ke watchlist`)}
                    >
                      <i className="bi bi-bookmark-plus"></i>
                    </Button>
                  </td>
                </tr>
              ))
            ) : (
              <tr>
                <td colSpan="6" className="text-center py-3">
                  {searchTerm ? 'Tidak ada hasil yang sesuai dengan pencarian' : 'Tidak ada data saham tersedia'}
                </td>
              </tr>
            )}
          </tbody>
        </Table>
      </div>
      
      <div className="d-flex justify-content-between align-items-center mt-3">
        <div>
          Menampilkan {indexOfFirstItem + 1}-{Math.min(indexOfLastItem, filteredStocks.length)} dari {filteredStocks.length} saham
        </div>
        <Pagination>
          <Pagination.First 
            onClick={() => paginate(1)} 
            disabled={currentPage === 1}
          />
          <Pagination.Prev 
            onClick={() => paginate(currentPage - 1)}
            disabled={currentPage === 1}
          />
          {paginationItems}
          <Pagination.Next 
            onClick={() => paginate(currentPage + 1)}
            disabled={currentPage === totalPages}
          />
          <Pagination.Last 
            onClick={() => paginate(totalPages)}
            disabled={currentPage === totalPages}
          />
        </Pagination>
      </div>
    </div>
  );
};

export default StockTable;

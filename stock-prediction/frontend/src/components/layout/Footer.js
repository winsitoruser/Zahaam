import React from 'react';

const Footer = () => {
  const currentYear = new Date().getFullYear();
  
  return (
    <footer className="footer py-4 mt-5">
      <div className="container">
        <div className="row">
          <div className="col-md-4">
            <h5 className="fw-bold">ZAHAAM</h5>
            <p className="text-muted">Platform analisis dan prediksi saham dengan teknologi AI untuk pasar Indonesia.</p>
          </div>
          <div className="col-md-4">
            <h5 className="fw-bold">Quick Links</h5>
            <ul className="list-unstyled">
              <li><a href="/dashboard" className="text-decoration-none text-muted">Dashboard</a></li>
              <li><a href="/stocks" className="text-decoration-none text-muted">Stock List</a></li>
              <li><a href="/prediction" className="text-decoration-none text-muted">Prediction</a></li>
              <li><a href="/strategy" className="text-decoration-none text-muted">Trading Strategies</a></li>
            </ul>
          </div>
          <div className="col-md-4">
            <h5 className="fw-bold">Contact Us</h5>
            <ul className="list-unstyled">
              <li><i className="bi bi-envelope me-2"></i> info@zahaam.com</li>
              <li><i className="bi bi-telephone me-2"></i> +62 21 12345678</li>
              <li><i className="bi bi-geo-alt me-2"></i> Jakarta, Indonesia</li>
            </ul>
            <div className="social-links mt-3">
              <a href="#" className="me-2 text-muted"><i className="bi bi-twitter"></i></a>
              <a href="#" className="me-2 text-muted"><i className="bi bi-facebook"></i></a>
              <a href="#" className="me-2 text-muted"><i className="bi bi-instagram"></i></a>
              <a href="#" className="me-2 text-muted"><i className="bi bi-linkedin"></i></a>
            </div>
          </div>
        </div>
        <div className="border-top pt-3 mt-3">
          <div className="row">
            <div className="col-md-6 small">
              <p className="text-muted mb-md-0">© {currentYear} ZAHAAM. All rights reserved.</p>
            </div>
            <div className="col-md-6 text-md-end small">
              <a href="#" className="text-decoration-none text-muted me-3">Privacy Policy</a>
              <a href="#" className="text-decoration-none text-muted me-3">Terms of Service</a>
              <a href="#" className="text-decoration-none text-muted">FAQ</a>
            </div>
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;

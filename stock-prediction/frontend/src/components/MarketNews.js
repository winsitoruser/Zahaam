import React, { useState, useEffect } from 'react';
import { Row, Col, Card, Button, Spinner } from 'react-bootstrap';

const MarketNews = () => {
  const [news, setNews] = useState([]);
  const [loading, setLoading] = useState(true);
  
  useEffect(() => {
    // Simulate fetching news data
    const fetchNews = async () => {
      try {
        setLoading(true);
        // This would normally be an API call to a news service
        // For demonstration, we'll use static data
        
        // Simulate API delay
        await new Promise(resolve => setTimeout(resolve, 1000));
        
        const mockNews = [
          {
            id: 1,
            title: 'Bank Indonesia Maintains Key Interest Rate at 5.75%',
            summary: 'Bank Indonesia decided to maintain its benchmark interest rate at 5.75% in its latest monetary policy meeting, aligning with market expectations amid stable inflation and efforts to support economic growth.',
            source: 'Jakarta Finance News',
            publishedAt: '2025-05-29T08:45:00Z',
            url: '#',
            imageUrl: 'https://images.unsplash.com/photo-1607944024060-0450380ddd33?q=80&w=2000&auto=format&fit=crop'
          },
          {
            id: 2,
            title: 'Indonesian Markets Rally on Positive Economic Outlook',
            summary: 'The Jakarta Composite Index (JCI) surged by 1.8% today, reaching its highest level in six months, driven by strong corporate earnings and positive economic indicators suggesting continued growth in domestic consumption.',
            source: 'Indonesia Market Watch',
            publishedAt: '2025-05-29T10:30:00Z',
            url: '#',
            imageUrl: 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?q=80&w=2000&auto=format&fit=crop'
          },
          {
            id: 3,
            title: 'Telkom Indonesia Reports 15% Increase in Q1 Profits',
            summary: 'PT Telkom Indonesia (TLKM.JK) announced a 15% year-on-year increase in first-quarter profits, exceeding analyst expectations, driven by strong growth in its digital business segment and expanding mobile data services.',
            source: 'Business Indonesia',
            publishedAt: '2025-05-28T14:15:00Z',
            url: '#',
            imageUrl: 'https://images.unsplash.com/photo-1563986768494-4dee2763ff3f?q=80&w=2000&auto=format&fit=crop'
          },
          {
            id: 4,
            title: "Indonesia's Manufacturing PMI Rises to 53.2 in May",
            summary: "Indonesia's Manufacturing Purchasing Managers' Index (PMI) rose to 53.2 in May from 52.8 in April, indicating continued expansion in the manufacturing sector amid improved domestic and export demand.",
            source: "Economic Times Indonesia",
            publishedAt: "2025-05-28T09:20:00Z",
            url: "#",
            imageUrl: "https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?q=80&w=2000&auto=format&fit=crop"
          },
          {
            id: 5,
            title: "Foreign Investors Increase Holdings in Indonesian Government Bonds",
            summary: "Foreign investors have increased their holdings of Indonesian government bonds by $2.3 billion in May, reflecting growing confidence in Indonesia's economic stability and attractive yield prospects compared to other emerging markets.",
            source: "Jakarta Economic Review",
            publishedAt: "2025-05-27T16:40:00Z",
            url: "#",
            imageUrl: "https://images.unsplash.com/photo-1534951009808-766178b47a4f?q=80&w=2000&auto=format&fit=crop"
          },
          {
            id: 6,
            title: "Indonesian Government Announces New Infrastructure Investment Package",
            summary: "The Indonesian government unveiled a new $15 billion infrastructure investment package focused on expanding port facilities, highways, and power generation, aiming to boost economic growth and improve logistics efficiency across the archipelago.",
            source: "Indonesia Infrastructure News",
            publishedAt: "2025-05-27T11:10:00Z",
            url: "#",
            imageUrl: "https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?q=80&w=2000&auto=format&fit=crop"
          }
        ];
        
        setNews(mockNews);
      } catch (error) {
        console.error('Error fetching news:', error);
      } finally {
        setLoading(false);
      }
    };
    
    fetchNews();
  }, []);
  
  // Format date
  const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    });
  };
  
  if (loading) {
    return (
      <div className="text-center py-4">
        <Spinner animation="border" variant="primary" />
        <p className="mt-2">Loading market news...</p>
      </div>
    );
  }
  
  return (
    <>
      <Row className="g-4">
        {news.map(item => (
          <Col key={item.id} lg={4} md={6}>
            <Card className="news-card h-100">
              <Card.Img variant="top" src={item.imageUrl} className="news-image" />
              <Card.Body>
                <div className="d-flex justify-content-between align-items-center mb-2">
                  <span className="news-source">{item.source}</span>
                  <span className="news-date">{formatDate(item.publishedAt)}</span>
                </div>
                <h5 className="news-title">{item.title}</h5>
                <p className="news-summary">{item.summary}</p>
              </Card.Body>
              <Card.Footer className="bg-white border-0">
                <Button variant="outline-primary" size="sm" href={item.url} className="w-100">
                  Read More
                </Button>
              </Card.Footer>
            </Card>
          </Col>
        ))}
      </Row>
      
      <div className="text-center mt-4">
        <Button variant="primary">
          Load More News
        </Button>
      </div>
    </>
  );
};

export default MarketNews;

import { useState, useEffect, useMemo } from 'react';
import './App.css';

const API_URL = 'http://localhost:5000/api/books';

function App() {
  const [books, setBooks] = useState([]);
  const [formData, setFormData] = useState({
    title: '',
    author: '',
    genre: '',
    year: ''
  });
  const [editingId, setEditingId] = useState(null);
  const [loading, setLoading] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');

  // Fetch books
  const fetchBooks = async () => {
    try {
      const response = await fetch(API_URL);
      const data = await response.json();
      setBooks(data);
    } catch (error) {
      console.error('Error fetching books:', error);
    }
  };

  useEffect(() => {
    fetchBooks();
  }, []);

  // Filter books based on search query
  const filteredBooks = useMemo(() => {
    return books.filter(book => 
      book.title.toLowerCase().includes(searchQuery.toLowerCase()) ||
      book.author.toLowerCase().includes(searchQuery.toLowerCase()) ||
      book.genre.toLowerCase().includes(searchQuery.toLowerCase())
    );
  }, [books, searchQuery]);

  // Handle form input
  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  // Add or Update book
  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    
    const method = editingId ? 'PUT' : 'POST';
    const url = editingId ? `${API_URL}/${editingId}` : API_URL;

    try {
      const response = await fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData),
      });

      if (response.ok) {
        setFormData({ title: '', author: '', genre: '', year: '' });
        setEditingId(null);
        fetchBooks();
      }
    } catch (error) {
      console.error('Error saving book:', error);
    } finally {
      setLoading(false);
    }
  };

  // Delete book
  const handleDelete = async (id) => {
    if (!window.confirm('Are you sure you want to delete this book?')) return;
    
    try {
      const response = await fetch(`${API_URL}/${id}`, { method: 'DELETE' });
      if (response.ok) {
        fetchBooks();
      }
    } catch (error) {
      console.error('Error deleting book:', error);
    }
  };

  // Edit book (populate form)
  const handleEdit = (book) => {
    setFormData({
      title: book.title,
      author: book.author,
      genre: book.genre,
      year: book.year
    });
    setEditingId(book._id);
    // Smooth scroll to top on mobile
    if (window.innerWidth < 1024) {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  };

  return (
    <div className="dashboard">
      {/* Sidebar - Add/Edit Form */}
      <aside className="sidebar glass">
        <h2 className="gradient-text" style={{ fontSize: '1.75rem', marginBottom: '2.5rem' }}>
          {editingId ? 'Edit Archive' : 'Add to Collection'}
        </h2>
        
        <form onSubmit={handleSubmit} style={{ display: 'flex', flexDirection: 'column', gap: '1.25rem' }}>
          <div className="form-group">
            <input
              type="text"
              name="title"
              value={formData.title}
              onChange={handleChange}
              placeholder="Book Title"
              required
            />
          </div>
          <div className="form-group">
            <input
              type="text"
              name="author"
              value={formData.author}
              onChange={handleChange}
              placeholder="Author Name"
              required
            />
          </div>
          <div className="form-group">
            <input
              type="text"
              name="genre"
              value={formData.genre}
              onChange={handleChange}
              placeholder="Genre (e.g. Science Fiction)"
              required
            />
          </div>
          <div className="form-group">
            <input
              type="number"
              name="year"
              value={formData.year}
              onChange={handleChange}
              placeholder="Publication Year"
              required
            />
          </div>
          
          <button type="submit" className="btn-primary" disabled={loading}>
            {loading ? 'Processing...' : (editingId ? 'Update Entry' : 'Save to Library')}
          </button>
          
          {editingId && (
            <button 
              type="button" 
              onClick={() => { setEditingId(null); setFormData({ title: '', author: '', genre: '', year: '' }); }}
              style={{ background: 'transparent', border: '1px solid var(--glass-border)', color: 'var(--text-dim)' }}
            >
              Cancel Edit
            </button>
          )}
        </form>

        <div style={{ marginTop: 'auto', paddingTop: '2rem' }}>
          <p style={{ color: 'var(--text-dim)', fontSize: '0.85rem' }}>
            &copy; 2026 Celestial Library System v2.0
          </p>
        </div>
      </aside>

      {/* Main Content - Search & Grid */}
      <main className="main-content">
        <header style={{ marginBottom: '3rem', display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap', gap: '2rem' }}>
          <div>
            <h1 className="gradient-text" style={{ fontSize: '3rem', marginBottom: '0.5rem' }}>The Celestial Library</h1>
            <p style={{ color: 'var(--text-dim)', fontSize: '1.1rem' }}>Your personal gateway to infinite knowledge</p>
          </div>
          
          <div style={{ position: 'relative', width: '100%', maxWidth: '400px' }}>
            <input 
              type="text" 
              placeholder="Search your collection..." 
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
              style={{ paddingLeft: '3rem' }}
            />
            <span style={{ position: 'absolute', left: '1.25rem', top: '50%', transform: 'translateY(-50%)', opacity: 0.5 }}>
              🔍
            </span>
          </div>
        </header>

        <section>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '2rem' }}>
            <h2 style={{ fontSize: '1.5rem' }}>
              Your Archive <span style={{ color: 'var(--primary)', opacity: 0.8 }}>({filteredBooks.length})</span>
            </h2>
          </div>

          <div className="book-grid">
            {filteredBooks.length === 0 ? (
              <div className="glass" style={{ gridColumn: '1/-1', textAlign: 'center', padding: '5rem', borderRadius: '32px' }}>
                <p style={{ color: 'var(--text-dim)', fontSize: '1.2rem', marginBottom: '1rem' }}>
                  {searchQuery ? "No books match your search." : "Your archive is currently empty."}
                </p>
                <p style={{ color: 'var(--text-dim)', opacity: 0.6 }}>
                  {searchQuery ? "Try a different keyword." : "Start by adding your first masterpiece to the collection."}
                </p>
              </div>
            ) : (
              filteredBooks.map((book) => (
                <div key={book._id} className="book-card glass">
                  <div className="genre-badge" style={{ marginBottom: '1rem' }}>{book.genre}</div>
                  <h3 style={{ fontSize: '1.4rem', marginBottom: '0.5rem', lineHeight: '1.2' }}>{book.title}</h3>
                  <p style={{ marginBottom: '1rem', color: 'var(--text-dim)' }}>by {book.author}</p>
                  
                  <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginTop: 'auto' }}>
                    <span style={{ opacity: 0.6, fontSize: '0.9rem' }}>est. {book.year}</span>
                    <div style={{ display: 'flex', gap: '0.5rem' }}>
                      <button 
                        onClick={() => handleEdit(book)}
                        style={{ padding: '0.5rem', background: 'rgba(255,255,255,0.05)', borderRadius: '10px' }}
                        title="Edit entry"
                      >
                        ✏️
                      </button>
                      <button 
                        onClick={() => handleDelete(book._id)}
                        style={{ padding: '0.5rem', background: 'rgba(251, 113, 133, 0.1)', color: 'var(--error)', borderRadius: '10px' }}
                        title="Delete entry"
                      >
                        🗑️
                      </button>
                    </div>
                  </div>
                </div>
              ))
            )}
          </div>
        </section>
      </main>
    </div>
  );
}

export default App;


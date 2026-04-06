import { useState, useEffect } from 'react';
import axios from 'axios';
import toast from 'react-hot-toast';

const API = 'http://localhost:5000/api/books';

const emptyForm = {
  bookName: '', isbnNo: '', bookTitle: '', authorName: '', publisherName: '',
};

export default function LibraryManagement() {
  const [books, setBooks] = useState([]);
  const [form, setForm] = useState(emptyForm);
  const [loading, setLoading] = useState(false);
  const [deleteIsbn, setDeleteIsbn] = useState('');
  const [searchIsbn, setSearchIsbn] = useState('');
  const [editData, setEditData] = useState(null);
  const [showEditModal, setShowEditModal] = useState(false);

  useEffect(() => { fetchAll(); }, []);

  const fetchAll = async () => {
    try {
      const { data } = await axios.get(API);
      setBooks(data);
    } catch { toast.error('Failed to fetch books'); }
  };

  const handleChange = (e) => setForm({ ...form, [e.target.name]: e.target.value });

  const handleInsert = async (e) => {
    e.preventDefault();
    setLoading(true);
    try {
      await axios.post(API, form);
      toast.success('Book inserted!');
      setForm(emptyForm);
      fetchAll();
    } catch (err) {
      toast.error(err.response?.data?.message || 'Insert failed');
    } finally { setLoading(false); }
  };

  const handleDelete = async () => {
    if (!deleteIsbn.trim()) return toast.error('Enter an ISBN No');
    try {
      await axios.delete(`${API}/${deleteIsbn}`);
      toast.success('Book deleted!');
      setDeleteIsbn('');
      fetchAll();
    } catch (err) { toast.error(err.response?.data?.message || 'Delete failed'); }
  };

  const handleSearch = async () => {
    if (!searchIsbn.trim()) return toast.error('Enter an ISBN No');
    try {
      const { data } = await axios.get(`${API}/${searchIsbn}`);
      setEditData(data);
      setShowEditModal(true);
    } catch (err) { toast.error(err.response?.data?.message || 'Book not found'); }
  };

  const handleUpdate = async () => {
    try {
      await axios.put(`${API}/${editData.isbnNo}`, editData);
      toast.success('Book updated!');
      setShowEditModal(false);
      setSearchIsbn('');
      fetchAll();
    } catch (err) { toast.error(err.response?.data?.message || 'Update failed'); }
  };

  const handleInlineDelete = async (isbnNo) => {
    if (!window.confirm(`Delete: ${isbnNo}?`)) return;
    try {
      await axios.delete(`${API}/${isbnNo}`);
      toast.success('Deleted!');
      fetchAll();
    } catch (err) { toast.error(err.response?.data?.message || 'Delete failed'); }
  };

  return (
    <div className="page-container">
      <div className="page-header">
        <div className="page-tag" style={{ color: 'var(--lime)', background: 'var(--lime-muted)', borderColor: 'var(--border-lime)' }}>
          02 · Library Management
        </div>
        <h1 className="page-title">Library <span style={{ color: 'var(--lime)' }}>Management</span></h1>
        <p className="page-subtitle">Insert, update, delete and view book records. Primary key: ISBN No.</p>
      </div>

      <div className="crud-grid">
        {/* INSERT */}
        <div className="card form-section" style={{ borderColor: 'rgba(163,230,53,0.1)' }}>
          <div className="card-header">
            <div className="card-header-title">
              <div className="card-header-icon" style={{ background: 'var(--lime-muted)', borderColor: 'var(--border-lime)' }}>➕</div>
              Insert New Book
            </div>
          </div>
          <div className="card-body">
            <form onSubmit={handleInsert}>
              <div className="form-grid">
                {[
                  { label: 'Book Name', name: 'bookName', ph: 'The Great Gatsby' },
                  { label: 'ISBN No', name: 'isbnNo', ph: '978-3-16-148410-0' },
                  { label: 'Book Title', name: 'bookTitle', ph: 'Full title here' },
                  { label: 'Author Name', name: 'authorName', ph: 'F. Scott Fitzgerald' },
                  { label: 'Publisher Name', name: 'publisherName', ph: 'Scribner' },
                ].map(({ label, name, ph }) => (
                  <div className="form-group" key={name}>
                    <label className="form-label">{label}</label>
                    <input className="form-input" name={name} value={form[name]}
                      onChange={handleChange} placeholder={ph} required
                      style={{ '--focus-color': 'var(--lime)', '--focus-bg': 'rgba(163,230,53,0.04)', '--focus-shadow': 'rgba(163,230,53,0.12)' }}
                      onFocus={(e) => { e.target.style.borderColor = 'var(--lime)'; e.target.style.boxShadow = '0 0 0 3px rgba(163,230,53,0.12)'; }}
                      onBlur={(e) => { e.target.style.borderColor = ''; e.target.style.boxShadow = ''; }} />
                  </div>
                ))}
              </div>
              <div className="btn-group">
                <button className="btn btn-success" type="submit" disabled={loading}>
                  {loading ? <span className="spinner" style={{ borderTopColor: 'var(--navy-950)' }} /> : null} Insert Book
                </button>
                <button className="btn btn-secondary" type="button" onClick={() => setForm(emptyForm)}>Reset</button>
              </div>
            </form>
          </div>
        </div>

        {/* ACTIONS */}
        <div className="card action-section" style={{ borderColor: 'rgba(163,230,53,0.1)' }}>
          <div className="card-header">
            <div className="card-header-title">
              <div className="card-header-icon" style={{ background: 'var(--lime-muted)', borderColor: 'var(--border-lime)' }}>⚙</div>
              Operations
            </div>
          </div>
          <div className="card-body">
            <div className="action-panel">
              <div className="action-group">
                <div className="divider-label">Delete by ISBN No</div>
                <div className="action-row">
                  <input className="form-input" value={deleteIsbn}
                    onChange={(e) => setDeleteIsbn(e.target.value)}
                    placeholder="ISBN No to delete" />
                  <button className="btn btn-danger" onClick={handleDelete}>Delete</button>
                </div>
              </div>
              <div className="action-group">
                <div className="divider-label">Update by ISBN No</div>
                <div className="action-row">
                  <input className="form-input" value={searchIsbn}
                    onChange={(e) => setSearchIsbn(e.target.value)}
                    placeholder="ISBN No to update" />
                  <button className="btn btn-warning" onClick={handleSearch}>Search</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* TABLE */}
        <div className="card table-section">
          <div className="table-toolbar">
            <div className="table-title">
              All Books
              <span className="pill pill-lime">{books.length} records</span>
            </div>
          </div>
          <div className="table-wrapper">
            {books.length === 0 ? (
              <div className="empty-state">
                <div className="empty-state-icon">📚</div>
                <p>No books found. Insert a record above.</p>
              </div>
            ) : (
              <table>
                <thead>
                  <tr>
                    <th>#</th><th>ISBN No</th><th>Book Name</th>
                    <th>Book Title</th><th>Author</th><th>Publisher</th><th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  {books.map((b, i) => (
                    <tr key={b._id}>
                      <td className="td-mono" style={{ color: 'var(--text-400)' }}>{String(i+1).padStart(2,'0')}</td>
                      <td className="td-mono td-highlight-lime">{b.isbnNo}</td>
                      <td>{b.bookName}</td>
                      <td>{b.bookTitle}</td>
                      <td>{b.authorName}</td>
                      <td>{b.publisherName}</td>
                      <td>
                        <div className="actions-cell">
                          <button className="btn btn-sm btn-warning" onClick={() => { setEditData({...b}); setShowEditModal(true); }}>Edit</button>
                          <button className="btn btn-sm btn-danger" onClick={() => handleInlineDelete(b.isbnNo)}>Del</button>
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            )}
          </div>
        </div>
      </div>

      {/* MODAL */}
      {showEditModal && editData && (
        <div className="modal-overlay" onClick={(e) => e.target === e.currentTarget && setShowEditModal(false)}>
          <div className="modal-box" style={{ borderColor: 'var(--border-lime)', boxShadow: '0 20px 60px rgba(0,0,0,0.6), var(--glow-lime)' }}>
            <div className="modal-header">
              <div className="modal-title">✏ Edit Book — <span style={{ color: 'var(--lime)', fontFamily: 'JetBrains Mono' }}>{editData.isbnNo}</span></div>
              <button className="modal-close" onClick={() => setShowEditModal(false)}>✕</button>
            </div>
            <div className="modal-body">
              <div className="form-grid">
                {[
                  { label: 'Book Name', field: 'bookName' },
                  { label: 'Book Title', field: 'bookTitle' },
                  { label: 'Author Name', field: 'authorName' },
                  { label: 'Publisher Name', field: 'publisherName' },
                ].map(({ label, field }) => (
                  <div className="form-group" key={field}>
                    <label className="form-label">{label}</label>
                    <input className="form-input" value={editData[field] || ''}
                      onChange={(e) => setEditData({ ...editData, [field]: e.target.value })} />
                  </div>
                ))}
              </div>
            </div>
            <div className="modal-footer">
              <button className="btn btn-secondary" onClick={() => setShowEditModal(false)}>Cancel</button>
              <button className="btn btn-success" onClick={handleUpdate}>Save Changes</button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

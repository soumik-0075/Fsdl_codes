import { useState, useEffect } from 'react';
import axios from 'axios';
import toast from 'react-hot-toast';

const API = 'http://localhost:5000/api/students';

const emptyForm = {
  firstName: '', lastName: '', rollNo: '',
  password: '', confirmPassword: '', contactNumber: '',
};

export default function StudentManagement() {
  const [students, setStudents] = useState([]);
  const [form, setForm] = useState(emptyForm);
  const [loading, setLoading] = useState(false);
  const [deleteRollNo, setDeleteRollNo] = useState('');
  const [searchRollNo, setSearchRollNo] = useState('');
  const [editData, setEditData] = useState(null);
  const [showEditModal, setShowEditModal] = useState(false);

  useEffect(() => { fetchAll(); }, []);

  const fetchAll = async () => {
    try {
      const { data } = await axios.get(API);
      setStudents(data);
    } catch { toast.error('Failed to fetch students'); }
  };

  const handleChange = (e) => setForm({ ...form, [e.target.name]: e.target.value });

  const handleInsert = async (e) => {
    e.preventDefault();
    if (form.password !== form.confirmPassword) return toast.error('Passwords do not match!');
    setLoading(true);
    try {
      await axios.post(API, form);
      toast.success('Student inserted!');
      setForm(emptyForm);
      fetchAll();
    } catch (err) {
      toast.error(err.response?.data?.message || 'Insert failed');
    } finally { setLoading(false); }
  };

  const handleDelete = async () => {
    if (!deleteRollNo.trim()) return toast.error('Enter a Roll No');
    try {
      await axios.delete(`${API}/${deleteRollNo}`);
      toast.success('Student deleted!');
      setDeleteRollNo('');
      fetchAll();
    } catch (err) { toast.error(err.response?.data?.message || 'Delete failed'); }
  };

  const handleSearch = async () => {
    if (!searchRollNo.trim()) return toast.error('Enter a Roll No');
    try {
      const { data } = await axios.get(`${API}/${searchRollNo}`);
      setEditData(data);
      setShowEditModal(true);
    } catch (err) { toast.error(err.response?.data?.message || 'Student not found'); }
  };

  const handleUpdate = async () => {
    try {
      await axios.put(`${API}/${editData.rollNo}`, editData);
      toast.success('Student updated!');
      setShowEditModal(false);
      setSearchRollNo('');
      fetchAll();
    } catch (err) { toast.error(err.response?.data?.message || 'Update failed'); }
  };

  const handleInlineDelete = async (rollNo) => {
    if (!window.confirm(`Delete student: ${rollNo}?`)) return;
    try {
      await axios.delete(`${API}/${rollNo}`);
      toast.success('Deleted!');
      fetchAll();
    } catch (err) { toast.error(err.response?.data?.message || 'Delete failed'); }
  };

  return (
    <div className="page-container">
      {/* PAGE HEADER */}
      <div className="page-header">
        <div className="page-tag">01 · Student Registration</div>
        <h1 className="page-title">Student <span>Management</span></h1>
        <p className="page-subtitle">Insert, update, delete and view student records. Primary key: Roll No.</p>
      </div>

      <div className="crud-grid">
        {/* INSERT */}
        <div className="card form-section">
          <div className="card-header">
            <div className="card-header-title">
              <div className="card-header-icon">➕</div>
              Insert New Student
            </div>
          </div>
          <div className="card-body">
            <form onSubmit={handleInsert}>
              <div className="form-grid">
                {[
                  { label: 'First Name', name: 'firstName', ph: 'John' },
                  { label: 'Last Name', name: 'lastName', ph: 'Doe' },
                  { label: 'Roll No / ID', name: 'rollNo', ph: 'CS2024001' },
                  { label: 'Contact Number', name: 'contactNumber', ph: '+91 98765 43210' },
                  { label: 'Password', name: 'password', ph: '••••••••', type: 'password' },
                  { label: 'Confirm Password', name: 'confirmPassword', ph: '••••••••', type: 'password' },
                ].map(({ label, name, ph, type = 'text' }) => (
                  <div className="form-group" key={name}>
                    <label className="form-label">{label}</label>
                    <input className="form-input" type={type} name={name} value={form[name]}
                      onChange={handleChange} placeholder={ph} required />
                  </div>
                ))}
              </div>
              <div className="btn-group">
                <button className="btn btn-primary" type="submit" disabled={loading}>
                  {loading ? <span className="spinner" /> : null} Insert Student
                </button>
                <button className="btn btn-secondary" type="button" onClick={() => setForm(emptyForm)}>
                  Reset
                </button>
              </div>
            </form>
          </div>
        </div>

        {/* DELETE + UPDATE */}
        <div className="card action-section">
          <div className="card-header">
            <div className="card-header-title">
              <div className="card-header-icon">⚙</div>
              Operations
            </div>
          </div>
          <div className="card-body">
            <div className="action-panel">
              <div className="action-group">
                <div className="divider-label">Delete by Roll No</div>
                <div className="action-row">
                  <input className="form-input" value={deleteRollNo}
                    onChange={(e) => setDeleteRollNo(e.target.value)}
                    placeholder="Roll No to delete" />
                  <button className="btn btn-danger" onClick={handleDelete}>Delete</button>
                </div>
              </div>
              <div className="action-group">
                <div className="divider-label">Update by Roll No</div>
                <div className="action-row">
                  <input className="form-input" value={searchRollNo}
                    onChange={(e) => setSearchRollNo(e.target.value)}
                    placeholder="Roll No to update" />
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
              All Students
              <span className="pill pill-cyan">{students.length} records</span>
            </div>
          </div>
          <div className="table-wrapper">
            {students.length === 0 ? (
              <div className="empty-state">
                <div className="empty-state-icon">🎓</div>
                <p>No students found. Insert a record above.</p>
              </div>
            ) : (
              <table>
                <thead>
                  <tr>
                    <th>#</th><th>Roll No</th><th>First Name</th>
                    <th>Last Name</th><th>Contact</th><th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  {students.map((s, i) => (
                    <tr key={s._id}>
                      <td className="td-mono" style={{ color: 'var(--text-400)' }}>{String(i+1).padStart(2,'0')}</td>
                      <td className="td-mono td-highlight-cyan">{s.rollNo}</td>
                      <td>{s.firstName}</td>
                      <td>{s.lastName}</td>
                      <td className="td-mono">{s.contactNumber}</td>
                      <td>
                        <div className="actions-cell">
                          <button className="btn btn-sm btn-warning" onClick={() => { setEditData({...s}); setShowEditModal(true); }}>Edit</button>
                          <button className="btn btn-sm btn-danger" onClick={() => handleInlineDelete(s.rollNo)}>Del</button>
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
          <div className="modal-box">
            <div className="modal-header">
              <div className="modal-title">✏ Edit Student — <span style={{ color: 'var(--cyan)', fontFamily: 'JetBrains Mono' }}>{editData.rollNo}</span></div>
              <button className="modal-close" onClick={() => setShowEditModal(false)}>✕</button>
            </div>
            <div className="modal-body">
              <div className="form-grid">
                {[
                  { label: 'First Name', field: 'firstName' },
                  { label: 'Last Name', field: 'lastName' },
                  { label: 'Contact Number', field: 'contactNumber' },
                  { label: 'Password', field: 'password', type: 'password' },
                  { label: 'Confirm Password', field: 'confirmPassword', type: 'password' },
                ].map(({ label, field, type = 'text' }) => (
                  <div className="form-group" key={field}>
                    <label className="form-label">{label}</label>
                    <input className="form-input" type={type}
                      value={editData[field] || ''}
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

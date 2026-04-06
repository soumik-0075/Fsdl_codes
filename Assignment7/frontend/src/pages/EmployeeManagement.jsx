import { useState, useEffect } from 'react';
import axios from 'axios';
import toast from 'react-hot-toast';

const API = 'http://localhost:5000/api/employees';

const emptyForm = {
  employeeName: '', employeeId: '', departmentName: '', phoneNumber: '', joiningDate: '',
};

export default function EmployeeManagement() {
  const [employees, setEmployees] = useState([]);
  const [form, setForm] = useState(emptyForm);
  const [loading, setLoading] = useState(false);
  const [deleteEmpId, setDeleteEmpId] = useState('');
  const [searchEmpId, setSearchEmpId] = useState('');
  const [editData, setEditData] = useState(null);
  const [showEditModal, setShowEditModal] = useState(false);

  useEffect(() => { fetchAll(); }, []);

  const fetchAll = async () => {
    try {
      const { data } = await axios.get(API);
      setEmployees(data);
    } catch { toast.error('Failed to fetch employees'); }
  };

  const handleChange = (e) => setForm({ ...form, [e.target.name]: e.target.value });

  const handleInsert = async (e) => {
    e.preventDefault();
    setLoading(true);
    try {
      await axios.post(API, form);
      toast.success('Employee inserted!');
      setForm(emptyForm);
      fetchAll();
    } catch (err) {
      toast.error(err.response?.data?.message || 'Insert failed');
    } finally { setLoading(false); }
  };

  const handleDelete = async () => {
    if (!deleteEmpId.trim()) return toast.error('Enter an Employee ID');
    try {
      await axios.delete(`${API}/${deleteEmpId}`);
      toast.success('Employee deleted!');
      setDeleteEmpId('');
      fetchAll();
    } catch (err) { toast.error(err.response?.data?.message || 'Delete failed'); }
  };

  const handleSearch = async () => {
    if (!searchEmpId.trim()) return toast.error('Enter an Employee ID');
    try {
      const { data } = await axios.get(`${API}/${searchEmpId}`);
      setEditData({ ...data, joiningDate: data.joiningDate?.split('T')[0] });
      setShowEditModal(true);
    } catch (err) { toast.error(err.response?.data?.message || 'Employee not found'); }
  };

  const handleUpdate = async () => {
    try {
      await axios.put(`${API}/${editData.employeeId}`, editData);
      toast.success('Employee updated!');
      setShowEditModal(false);
      setSearchEmpId('');
      fetchAll();
    } catch (err) { toast.error(err.response?.data?.message || 'Update failed'); }
  };

  const handleInlineDelete = async (employeeId) => {
    if (!window.confirm(`Delete employee: ${employeeId}?`)) return;
    try {
      await axios.delete(`${API}/${employeeId}`);
      toast.success('Deleted!');
      fetchAll();
    } catch (err) { toast.error(err.response?.data?.message || 'Delete failed'); }
  };

  const fmtDate = (d) => d ? new Date(d).toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' }) : '-';

  return (
    <div className="page-container">
      <div className="page-header">
        <div className="page-tag" style={{ color: 'var(--amber)', background: 'var(--amber-muted)', borderColor: 'rgba(251,191,36,0.3)' }}>
          03 · Employee Management
        </div>
        <h1 className="page-title">Employee <span style={{ color: 'var(--amber)' }}>Management</span></h1>
        <p className="page-subtitle">Insert, update, delete and view employee records. Primary key: Employee ID.</p>
      </div>

      <div className="crud-grid">
        {/* INSERT */}
        <div className="card form-section" style={{ borderColor: 'rgba(251,191,36,0.1)' }}>
          <div className="card-header">
            <div className="card-header-title">
              <div className="card-header-icon" style={{ background: 'var(--amber-muted)', borderColor: 'rgba(251,191,36,0.3)' }}>➕</div>
              Insert New Employee
            </div>
          </div>
          <div className="card-body">
            <form onSubmit={handleInsert}>
              <div className="form-grid">
                {[
                  { label: 'Employee Name', name: 'employeeName', ph: 'Jane Smith' },
                  { label: 'Employee ID', name: 'employeeId', ph: 'EMP-001' },
                  { label: 'Department Name', name: 'departmentName', ph: 'Engineering' },
                  { label: 'Phone Number', name: 'phoneNumber', ph: '+91 98765 43210' },
                ].map(({ label, name, ph }) => (
                  <div className="form-group" key={name}>
                    <label className="form-label">{label}</label>
                    <input className="form-input" name={name} value={form[name]}
                      onChange={handleChange} placeholder={ph} required
                      onFocus={(e) => { e.target.style.borderColor = 'var(--amber)'; e.target.style.boxShadow = '0 0 0 3px rgba(251,191,36,0.1)'; }}
                      onBlur={(e) => { e.target.style.borderColor = ''; e.target.style.boxShadow = ''; }} />
                  </div>
                ))}
                <div className="form-group">
                  <label className="form-label">Joining Date</label>
                  <input className="form-input" type="date" name="joiningDate"
                    value={form.joiningDate} onChange={handleChange} required
                    onFocus={(e) => { e.target.style.borderColor = 'var(--amber)'; e.target.style.boxShadow = '0 0 0 3px rgba(251,191,36,0.1)'; }}
                    onBlur={(e) => { e.target.style.borderColor = ''; e.target.style.boxShadow = ''; }} />
                </div>
              </div>
              <div className="btn-group">
                <button className="btn" type="submit" disabled={loading}
                  style={{ background: 'var(--amber)', color: 'var(--navy-950)', fontWeight: 700, border: 'none' }}>
                  {loading ? <span className="spinner" style={{ borderTopColor: 'var(--navy-950)' }} /> : null} Insert Employee
                </button>
                <button className="btn btn-secondary" type="button" onClick={() => setForm(emptyForm)}>Reset</button>
              </div>
            </form>
          </div>
        </div>

        {/* ACTIONS */}
        <div className="card action-section" style={{ borderColor: 'rgba(251,191,36,0.1)' }}>
          <div className="card-header">
            <div className="card-header-title">
              <div className="card-header-icon" style={{ background: 'var(--amber-muted)', borderColor: 'rgba(251,191,36,0.3)' }}>⚙</div>
              Operations
            </div>
          </div>
          <div className="card-body">
            <div className="action-panel">
              <div className="action-group">
                <div className="divider-label">Delete by Employee ID</div>
                <div className="action-row">
                  <input className="form-input" value={deleteEmpId}
                    onChange={(e) => setDeleteEmpId(e.target.value)}
                    placeholder="Employee ID to delete" />
                  <button className="btn btn-danger" onClick={handleDelete}>Delete</button>
                </div>
              </div>
              <div className="action-group">
                <div className="divider-label">Update by Employee ID</div>
                <div className="action-row">
                  <input className="form-input" value={searchEmpId}
                    onChange={(e) => setSearchEmpId(e.target.value)}
                    placeholder="Employee ID to update" />
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
              All Employees
              <span className="pill" style={{ background: 'var(--amber-muted)', color: 'var(--amber)', borderColor: 'rgba(251,191,36,0.3)' }}>
                {employees.length} records
              </span>
            </div>
          </div>
          <div className="table-wrapper">
            {employees.length === 0 ? (
              <div className="empty-state">
                <div className="empty-state-icon">👔</div>
                <p>No employees found. Insert a record above.</p>
              </div>
            ) : (
              <table>
                <thead>
                  <tr>
                    <th>#</th><th>Employee ID</th><th>Name</th>
                    <th>Department</th><th>Phone</th><th>Joining Date</th><th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  {employees.map((emp, i) => (
                    <tr key={emp._id}>
                      <td className="td-mono" style={{ color: 'var(--text-400)' }}>{String(i+1).padStart(2,'0')}</td>
                      <td className="td-mono td-highlight-amber">{emp.employeeId}</td>
                      <td>{emp.employeeName}</td>
                      <td>{emp.departmentName}</td>
                      <td className="td-mono">{emp.phoneNumber}</td>
                      <td className="td-mono">{fmtDate(emp.joiningDate)}</td>
                      <td>
                        <div className="actions-cell">
                          <button className="btn btn-sm btn-warning" onClick={() => {
                            setEditData({ ...emp, joiningDate: emp.joiningDate?.split('T')[0] });
                            setShowEditModal(true);
                          }}>Edit</button>
                          <button className="btn btn-sm btn-danger" onClick={() => handleInlineDelete(emp.employeeId)}>Del</button>
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
          <div className="modal-box" style={{ borderColor: 'rgba(251,191,36,0.4)', boxShadow: '0 20px 60px rgba(0,0,0,0.6), 0 0 30px rgba(251,191,36,0.1)' }}>
            <div className="modal-header">
              <div className="modal-title">✏ Edit Employee — <span style={{ color: 'var(--amber)', fontFamily: 'JetBrains Mono' }}>{editData.employeeId}</span></div>
              <button className="modal-close" onClick={() => setShowEditModal(false)}>✕</button>
            </div>
            <div className="modal-body">
              <div className="form-grid">
                {[
                  { label: 'Employee Name', field: 'employeeName' },
                  { label: 'Department Name', field: 'departmentName' },
                  { label: 'Phone Number', field: 'phoneNumber' },
                ].map(({ label, field }) => (
                  <div className="form-group" key={field}>
                    <label className="form-label">{label}</label>
                    <input className="form-input" value={editData[field] || ''}
                      onChange={(e) => setEditData({ ...editData, [field]: e.target.value })} />
                  </div>
                ))}
                <div className="form-group">
                  <label className="form-label">Joining Date</label>
                  <input className="form-input" type="date" value={editData.joiningDate || ''}
                    onChange={(e) => setEditData({ ...editData, joiningDate: e.target.value })} />
                </div>
              </div>
            </div>
            <div className="modal-footer">
              <button className="btn btn-secondary" onClick={() => setShowEditModal(false)}>Cancel</button>
              <button className="btn" onClick={handleUpdate}
                style={{ background: 'var(--amber)', color: 'var(--navy-950)', fontWeight: 700 }}>Save Changes</button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

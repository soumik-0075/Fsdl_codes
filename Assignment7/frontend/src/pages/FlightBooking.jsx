import { useState, useEffect } from 'react';
import axios from 'axios';
import toast from 'react-hot-toast';

const API = 'http://localhost:5000/api/flights';

const emptyForm = {
  passengerName: '', from: '', to: '', date: '',
  departureDate: '', arrivalDate: '', phoneNumber: '', emailId: '',
};

export default function FlightBooking() {
  const [flights, setFlights] = useState([]);
  const [form, setForm] = useState(emptyForm);
  const [loading, setLoading] = useState(false);
  const [deletePhone, setDeletePhone] = useState('');
  const [searchPhone, setSearchPhone] = useState('');
  const [editData, setEditData] = useState(null);
  const [showEditModal, setShowEditModal] = useState(false);

  useEffect(() => { fetchAll(); }, []);

  const fetchAll = async () => {
    try {
      const { data } = await axios.get(API);
      setFlights(data);
    } catch { toast.error('Failed to fetch bookings'); }
  };

  const handleChange = (e) => setForm({ ...form, [e.target.name]: e.target.value });

  const handleInsert = async (e) => {
    e.preventDefault();
    setLoading(true);
    try {
      await axios.post(API, form);
      toast.success('Booking inserted!');
      setForm(emptyForm);
      fetchAll();
    } catch (err) {
      toast.error(err.response?.data?.message || 'Insert failed');
    } finally { setLoading(false); }
  };

  const handleDelete = async () => {
    if (!deletePhone.trim()) return toast.error('Enter a Phone Number');
    try {
      await axios.delete(`${API}/${deletePhone}`);
      toast.success('Booking deleted!');
      setDeletePhone('');
      fetchAll();
    } catch (err) { toast.error(err.response?.data?.message || 'Delete failed'); }
  };

  const handleSearch = async () => {
    if (!searchPhone.trim()) return toast.error('Enter a Phone Number');
    try {
      const { data } = await axios.get(`${API}/${searchPhone}`);
      setEditData({
        ...data,
        date: data.date?.split('T')[0],
        departureDate: data.departureDate?.split('T')[0],
        arrivalDate: data.arrivalDate?.split('T')[0],
      });
      setShowEditModal(true);
    } catch (err) { toast.error(err.response?.data?.message || 'Booking not found'); }
  };

  const handleUpdate = async () => {
    try {
      await axios.put(`${API}/${editData.phoneNumber}`, editData);
      toast.success('Booking updated!');
      setShowEditModal(false);
      setSearchPhone('');
      fetchAll();
    } catch (err) { toast.error(err.response?.data?.message || 'Update failed'); }
  };

  const handleInlineDelete = async (phoneNumber) => {
    if (!window.confirm(`Delete booking for: ${phoneNumber}?`)) return;
    try {
      await axios.delete(`${API}/${phoneNumber}`);
      toast.success('Deleted!');
      fetchAll();
    } catch (err) { toast.error(err.response?.data?.message || 'Delete failed'); }
  };

  const fmtDate = (d) => d ? new Date(d).toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' }) : '-';

  const roseStyle = {
    focus: (e) => { e.target.style.borderColor = 'var(--rose)'; e.target.style.boxShadow = '0 0 0 3px rgba(244,63,94,0.1)'; },
    blur: (e) => { e.target.style.borderColor = ''; e.target.style.boxShadow = ''; },
  };

  return (
    <div className="page-container">
      <div className="page-header">
        <div className="page-tag" style={{ color: 'var(--rose)', background: 'var(--rose-muted)', borderColor: 'rgba(244,63,94,0.3)' }}>
          04 · Flight Booking
        </div>
        <h1 className="page-title">Flight <span style={{ color: 'var(--rose)' }}>Booking</span></h1>
        <p className="page-subtitle">Insert, update, delete and view flight bookings. Primary key: Phone Number.</p>
      </div>

      <div className="crud-grid">
        {/* INSERT */}
        <div className="card form-section" style={{ borderColor: 'rgba(244,63,94,0.1)' }}>
          <div className="card-header">
            <div className="card-header-title">
              <div className="card-header-icon" style={{ background: 'var(--rose-muted)', borderColor: 'rgba(244,63,94,0.3)' }}>✈</div>
              Insert New Booking
            </div>
          </div>
          <div className="card-body">
            <form onSubmit={handleInsert}>
              <div className="form-grid">
                <div className="form-group">
                  <label className="form-label">Passenger Name</label>
                  <input className="form-input" name="passengerName" value={form.passengerName}
                    onChange={handleChange} placeholder="John Doe" required
                    onFocus={roseStyle.focus} onBlur={roseStyle.blur} />
                </div>
                <div className="form-group">
                  <label className="form-label">From</label>
                  <input className="form-input" name="from" value={form.from}
                    onChange={handleChange} placeholder="Mumbai" required
                    onFocus={roseStyle.focus} onBlur={roseStyle.blur} />
                </div>
                <div className="form-group">
                  <label className="form-label">To</label>
                  <input className="form-input" name="to" value={form.to}
                    onChange={handleChange} placeholder="Delhi" required
                    onFocus={roseStyle.focus} onBlur={roseStyle.blur} />
                </div>
                <div className="form-group">
                  <label className="form-label">Travel Date</label>
                  <input className="form-input" type="date" name="date"
                    value={form.date} onChange={handleChange} required
                    onFocus={roseStyle.focus} onBlur={roseStyle.blur} />
                </div>
                <div className="form-group">
                  <label className="form-label">Departure Date</label>
                  <input className="form-input" type="date" name="departureDate"
                    value={form.departureDate} onChange={handleChange} required
                    onFocus={roseStyle.focus} onBlur={roseStyle.blur} />
                </div>
                <div className="form-group">
                  <label className="form-label">Arrival Date</label>
                  <input className="form-input" type="date" name="arrivalDate"
                    value={form.arrivalDate} onChange={handleChange} required
                    onFocus={roseStyle.focus} onBlur={roseStyle.blur} />
                </div>
                <div className="form-group">
                  <label className="form-label">Phone Number</label>
                  <input className="form-input" name="phoneNumber" value={form.phoneNumber}
                    onChange={handleChange} placeholder="+91 98765 43210" required
                    onFocus={roseStyle.focus} onBlur={roseStyle.blur} />
                </div>
                <div className="form-group">
                  <label className="form-label">Email ID</label>
                  <input className="form-input" type="email" name="emailId" value={form.emailId}
                    onChange={handleChange} placeholder="john@example.com" required
                    onFocus={roseStyle.focus} onBlur={roseStyle.blur} />
                </div>
              </div>
              <div className="btn-group">
                <button className="btn" type="submit" disabled={loading}
                  style={{ background: 'var(--rose)', color: '#fff', fontWeight: 700, border: 'none' }}>
                  {loading ? <span className="spinner" style={{ borderTopColor: '#fff' }} /> : null} Insert Booking
                </button>
                <button className="btn btn-secondary" type="button" onClick={() => setForm(emptyForm)}>Reset</button>
              </div>
            </form>
          </div>
        </div>

        {/* ACTIONS */}
        <div className="card action-section" style={{ borderColor: 'rgba(244,63,94,0.1)' }}>
          <div className="card-header">
            <div className="card-header-title">
              <div className="card-header-icon" style={{ background: 'var(--rose-muted)', borderColor: 'rgba(244,63,94,0.3)' }}>⚙</div>
              Operations
            </div>
          </div>
          <div className="card-body">
            <div className="action-panel">
              <div className="action-group">
                <div className="divider-label">Delete by Phone Number</div>
                <div className="action-row">
                  <input className="form-input" value={deletePhone}
                    onChange={(e) => setDeletePhone(e.target.value)}
                    placeholder="Phone No to delete" />
                  <button className="btn btn-danger" onClick={handleDelete}>Delete</button>
                </div>
              </div>
              <div className="action-group">
                <div className="divider-label">Update by Phone Number</div>
                <div className="action-row">
                  <input className="form-input" value={searchPhone}
                    onChange={(e) => setSearchPhone(e.target.value)}
                    placeholder="Phone No to update" />
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
              All Bookings
              <span className="pill" style={{ background: 'var(--rose-muted)', color: 'var(--rose)', borderColor: 'rgba(244,63,94,0.3)' }}>
                {flights.length} records
              </span>
            </div>
          </div>
          <div className="table-wrapper">
            {flights.length === 0 ? (
              <div className="empty-state">
                <div className="empty-state-icon">✈️</div>
                <p>No bookings found. Insert a record above.</p>
              </div>
            ) : (
              <table>
                <thead>
                  <tr>
                    <th>#</th><th>Passenger</th><th>Route</th>
                    <th>Date</th><th>Dep.</th><th>Arr.</th>
                    <th>Phone</th><th>Email</th><th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  {flights.map((f, i) => (
                    <tr key={f._id}>
                      <td className="td-mono" style={{ color: 'var(--text-400)' }}>{String(i+1).padStart(2,'0')}</td>
                      <td>{f.passengerName}</td>
                      <td className="td-mono" style={{ color: 'var(--rose)' }}>{f.from} → {f.to}</td>
                      <td className="td-mono">{fmtDate(f.date)}</td>
                      <td className="td-mono">{fmtDate(f.departureDate)}</td>
                      <td className="td-mono">{fmtDate(f.arrivalDate)}</td>
                      <td className="td-mono td-highlight-amber">{f.phoneNumber}</td>
                      <td>{f.emailId}</td>
                      <td>
                        <div className="actions-cell">
                          <button className="btn btn-sm btn-warning" onClick={() => {
                            setEditData({
                              ...f,
                              date: f.date?.split('T')[0],
                              departureDate: f.departureDate?.split('T')[0],
                              arrivalDate: f.arrivalDate?.split('T')[0],
                            });
                            setShowEditModal(true);
                          }}>Edit</button>
                          <button className="btn btn-sm btn-danger" onClick={() => handleInlineDelete(f.phoneNumber)}>Del</button>
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
          <div className="modal-box" style={{ borderColor: 'rgba(244,63,94,0.4)', boxShadow: '0 20px 60px rgba(0,0,0,0.6), 0 0 30px rgba(244,63,94,0.1)' }}>
            <div className="modal-header">
              <div className="modal-title">✏ Edit Booking — <span style={{ color: 'var(--rose)', fontFamily: 'JetBrains Mono' }}>{editData.phoneNumber}</span></div>
              <button className="modal-close" onClick={() => setShowEditModal(false)}>✕</button>
            </div>
            <div className="modal-body">
              <div className="form-grid">
                {[
                  { label: 'Passenger Name', field: 'passengerName' },
                  { label: 'From', field: 'from' },
                  { label: 'To', field: 'to' },
                  { label: 'Email ID', field: 'emailId' },
                ].map(({ label, field }) => (
                  <div className="form-group" key={field}>
                    <label className="form-label">{label}</label>
                    <input className="form-input" value={editData[field] || ''}
                      onChange={(e) => setEditData({ ...editData, [field]: e.target.value })} />
                  </div>
                ))}
                {[
                  { label: 'Travel Date', field: 'date' },
                  { label: 'Departure Date', field: 'departureDate' },
                  { label: 'Arrival Date', field: 'arrivalDate' },
                ].map(({ label, field }) => (
                  <div className="form-group" key={field}>
                    <label className="form-label">{label}</label>
                    <input className="form-input" type="date" value={editData[field] || ''}
                      onChange={(e) => setEditData({ ...editData, [field]: e.target.value })} />
                  </div>
                ))}
              </div>
            </div>
            <div className="modal-footer">
              <button className="btn btn-secondary" onClick={() => setShowEditModal(false)}>Cancel</button>
              <button className="btn" onClick={handleUpdate}
                style={{ background: 'var(--rose)', color: '#fff', fontWeight: 700 }}>Save Changes</button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

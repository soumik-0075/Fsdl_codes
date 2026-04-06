import { NavLink } from 'react-router-dom';

const navItems = [
  { path: '/', label: 'Home', icon: '✦' },
  { path: '/students', label: 'Students', icon: '❂' },
  { path: '/library', label: 'Library', icon: '✧' },
  { path: '/employees', label: 'Employees', icon: '⟡' },
  { path: '/flights', label: 'Flights', icon: '⋆' },
];

export default function Navbar() {
  return (
    <nav className="navbar">
      <NavLink to="/" className="navbar-brand">
        <div className="navbar-logo">✧</div>
        <span className="navbar-title">MERN<span>CRUD</span></span>
      </NavLink>
      <ul className="navbar-nav">
        {navItems.map(({ path, label, icon }) => (
          <li key={path}>
            <NavLink
              to={path}
              end={path === '/'}
              className={({ isActive }) => `nav-link ${isActive ? 'active' : ''}`}
            >
              {icon} {label}
            </NavLink>
          </li>
        ))}
      </ul>
    </nav>
  );
}

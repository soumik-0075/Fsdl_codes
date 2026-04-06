import { Link } from 'react-router-dom';

const systems = [
  {
    path: '/students',
    num: '01 / Student Registration',
    title: 'Student System',
    desc: 'Insert, search, update and delete student records using Roll No as the unique key.',
    icon: '✦',
    cls: 'hc-cyan',
    ops: ['Insert', 'Delete by Roll No', 'Update by Roll No', 'View Table'],
  },
  {
    path: '/library',
    num: '02 / Library Management',
    title: 'Library System',
    desc: 'Full CRUD for book records. Search and update entries using ISBN No.',
    icon: '✧',
    cls: 'hc-lime',
    ops: ['Insert', 'Delete by ISBN', 'Update by ISBN', 'View Table'],
  },
  {
    path: '/employees',
    num: '03 / Employee Management',
    title: 'Employee System',
    desc: 'Manage workforce data. Update or remove employees by their unique Employee ID.',
    icon: '⟡',
    cls: 'hc-amber',
    ops: ['Insert', 'Delete by Emp ID', 'Update by Emp ID', 'View Table'],
  },
  {
    path: '/flights',
    num: '04 / Flight Booking',
    title: 'Flight System',
    desc: 'Handle passenger booking records. Phone Number is used as the primary key.',
    icon: '⋆',
    cls: 'hc-rose',
    ops: ['Insert', 'Delete by Phone', 'Update by Phone', 'View Table'],
  },
];

export default function Home() {
  return (
    <div className="home-page">
      <div className="hero-eyebrow">MERN Stack &bull; Assignment 7 &bull; Arnav Rampurkar</div>
      <h1 className="hero-title">
        Ethereal Management
        <span className="accent">Systems.</span>
      </h1>
      <p className="hero-subtitle">
        A premium full-stack application leveraging MongoDB, Express, React, and Node.js. Elegantly engineered CRUD functionality built with modern glassmorphism aesthetics.
      </p>
      <div className="hero-grid">
        {systems.map((sys) => (
          <Link key={sys.path} to={sys.path} className={`hero-card ${sys.cls}`}>
            <div className="hc-num">{sys.num}</div>
            <div className="hc-icon-bg">{sys.icon}</div>
            <div className="hc-title">{sys.title}</div>
            <div className="hc-desc">{sys.desc}</div>
            <div className="hc-ops">
              {sys.ops.map((op) => (
                <span key={op} className="hc-op-tag">{op}</span>
              ))}
            </div>
            <div className="hc-arrow">Launch Dimension →</div>
          </Link>
        ))}
      </div>
    </div>
  );
}

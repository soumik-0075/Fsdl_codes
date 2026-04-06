import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Navbar from './components/Navbar';
import Home from './pages/Home';
import StudentManagement from './pages/StudentManagement';
import LibraryManagement from './pages/LibraryManagement';
import EmployeeManagement from './pages/EmployeeManagement';
import FlightBooking from './pages/FlightBooking';

function App() {
  return (
    <Router>
      <Navbar />
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/students" element={<StudentManagement />} />
        <Route path="/library" element={<LibraryManagement />} />
        <Route path="/employees" element={<EmployeeManagement />} />
        <Route path="/flights" element={<FlightBooking />} />
      </Routes>
    </Router>
  );
}

export default App;

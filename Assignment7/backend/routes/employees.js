const express = require('express');
const router = express.Router();
const Employee = require('../models/Employee');

// GET all employees
router.get('/', async (req, res) => {
  try {
    const employees = await Employee.find().sort({ createdAt: -1 });
    res.json(employees);
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
});

// GET employee by employeeId
router.get('/:employeeId', async (req, res) => {
  try {
    const employee = await Employee.findOne({ employeeId: req.params.employeeId });
    if (!employee) return res.status(404).json({ message: 'Employee not found' });
    res.json(employee);
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
});

// POST create employee
router.post('/', async (req, res) => {
  try {
    const employee = new Employee(req.body);
    const saved = await employee.save();
    res.status(201).json(saved);
  } catch (err) {
    if (err.code === 11000) {
      return res.status(400).json({ message: 'Employee ID already exists!' });
    }
    res.status(400).json({ message: err.message });
  }
});

// PUT update employee by employeeId
router.put('/:employeeId', async (req, res) => {
  try {
    const updated = await Employee.findOneAndUpdate(
      { employeeId: req.params.employeeId },
      req.body,
      { new: true, runValidators: true }
    );
    if (!updated) return res.status(404).json({ message: 'Employee not found' });
    res.json(updated);
  } catch (err) {
    res.status(400).json({ message: err.message });
  }
});

// DELETE employee by employeeId
router.delete('/:employeeId', async (req, res) => {
  try {
    const deleted = await Employee.findOneAndDelete({ employeeId: req.params.employeeId });
    if (!deleted) return res.status(404).json({ message: 'Employee not found' });
    res.json({ message: 'Employee deleted successfully' });
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
});

module.exports = router;

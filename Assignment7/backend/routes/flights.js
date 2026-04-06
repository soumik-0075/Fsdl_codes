const express = require('express');
const router = express.Router();
const Flight = require('../models/Flight');

// GET all flights
router.get('/', async (req, res) => {
  try {
    const flights = await Flight.find().sort({ createdAt: -1 });
    res.json(flights);
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
});

// GET flight by phoneNumber
router.get('/:phoneNumber', async (req, res) => {
  try {
    const flight = await Flight.findOne({ phoneNumber: req.params.phoneNumber });
    if (!flight) return res.status(404).json({ message: 'Flight booking not found' });
    res.json(flight);
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
});

// POST create flight booking
router.post('/', async (req, res) => {
  try {
    const flight = new Flight(req.body);
    const saved = await flight.save();
    res.status(201).json(saved);
  } catch (err) {
    if (err.code === 11000) {
      return res.status(400).json({ message: 'Phone number already has a booking!' });
    }
    res.status(400).json({ message: err.message });
  }
});

// PUT update flight by phoneNumber
router.put('/:phoneNumber', async (req, res) => {
  try {
    const updated = await Flight.findOneAndUpdate(
      { phoneNumber: req.params.phoneNumber },
      req.body,
      { new: true, runValidators: true }
    );
    if (!updated) return res.status(404).json({ message: 'Flight booking not found' });
    res.json(updated);
  } catch (err) {
    res.status(400).json({ message: err.message });
  }
});

// DELETE flight by phoneNumber
router.delete('/:phoneNumber', async (req, res) => {
  try {
    const deleted = await Flight.findOneAndDelete({ phoneNumber: req.params.phoneNumber });
    if (!deleted) return res.status(404).json({ message: 'Flight booking not found' });
    res.json({ message: 'Flight booking deleted successfully' });
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
});

module.exports = router;

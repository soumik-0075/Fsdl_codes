const express = require('express');
const router = express.Router();
const Book = require('../models/Book');

// GET all books
router.get('/', async (req, res) => {
  try {
    const books = await Book.find().sort({ createdAt: -1 });
    res.json(books);
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
});

// GET book by isbnNo
router.get('/:isbnNo', async (req, res) => {
  try {
    const book = await Book.findOne({ isbnNo: req.params.isbnNo });
    if (!book) return res.status(404).json({ message: 'Book not found' });
    res.json(book);
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
});

// POST create book
router.post('/', async (req, res) => {
  try {
    const book = new Book(req.body);
    const saved = await book.save();
    res.status(201).json(saved);
  } catch (err) {
    if (err.code === 11000) {
      return res.status(400).json({ message: 'ISBN No already exists!' });
    }
    res.status(400).json({ message: err.message });
  }
});

// PUT update book by isbnNo
router.put('/:isbnNo', async (req, res) => {
  try {
    const updated = await Book.findOneAndUpdate(
      { isbnNo: req.params.isbnNo },
      req.body,
      { new: true, runValidators: true }
    );
    if (!updated) return res.status(404).json({ message: 'Book not found' });
    res.json(updated);
  } catch (err) {
    res.status(400).json({ message: err.message });
  }
});

// DELETE book by isbnNo
router.delete('/:isbnNo', async (req, res) => {
  try {
    const deleted = await Book.findOneAndDelete({ isbnNo: req.params.isbnNo });
    if (!deleted) return res.status(404).json({ message: 'Book not found' });
    res.json({ message: 'Book deleted successfully' });
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
});

module.exports = router;

const mongoose = require('mongoose');

const bookSchema = new mongoose.Schema(
  {
    bookName: { type: String, required: true, trim: true },
    isbnNo: { type: String, required: true, unique: true, trim: true },
    bookTitle: { type: String, required: true, trim: true },
    authorName: { type: String, required: true, trim: true },
    publisherName: { type: String, required: true, trim: true },
  },
  { timestamps: true }
);

module.exports = mongoose.model('Book', bookSchema);

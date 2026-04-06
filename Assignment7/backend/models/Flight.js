const mongoose = require('mongoose');

const flightSchema = new mongoose.Schema(
  {
    passengerName: { type: String, required: true, trim: true },
    from: { type: String, required: true, trim: true },
    to: { type: String, required: true, trim: true },
    date: { type: Date, required: true },
    departureDate: { type: Date, required: true },
    arrivalDate: { type: Date, required: true },
    phoneNumber: { type: String, required: true, unique: true, trim: true },
    emailId: { type: String, required: true, trim: true },
  },
  { timestamps: true }
);

module.exports = mongoose.model('Flight', flightSchema);

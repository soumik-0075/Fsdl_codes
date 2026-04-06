const mongoose = require('mongoose');

const employeeSchema = new mongoose.Schema(
  {
    employeeName: { type: String, required: true, trim: true },
    employeeId: { type: String, required: true, unique: true, trim: true },
    departmentName: { type: String, required: true, trim: true },
    phoneNumber: { type: String, required: true, trim: true },
    joiningDate: { type: Date, required: true },
  },
  { timestamps: true }
);

module.exports = mongoose.model('Employee', employeeSchema);

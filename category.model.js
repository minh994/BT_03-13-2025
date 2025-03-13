const mongoose = require('mongoose');

const categorySchema = new mongoose.Schema({
    name: {
        type: String,
        required: true
    },
    description: {
        type: String,
        default: ''
    },
    isDeleted: {
        type: Boolean,
        default: false
    }
}, {
    timestamps: true // Tự động thêm created_at và updated_at
});

module.exports = mongoose.model('Category', categorySchema); 
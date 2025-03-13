const express = require('express');
const mongoose = require('mongoose');
const bodyParser = require('body-parser');
const categoryRoutes = require('./category.routes');

const app = express();

// Middleware
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// MongoDB connection
mongoose.connect('mongodb://localhost:27017/BTL_moi', {
    useNewUrlParser: true,
    useUnifiedTopology: true
})
.then(() => {
    console.log('Connected to MongoDB');
})
.catch((error) => {
    console.log('Error connecting to MongoDB:', error);
});

// Routes
app.use('/api/categories', categoryRoutes);

// Start server
const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`Server is running on port ${PORT}`);
}); 
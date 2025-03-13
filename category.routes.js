const express = require('express');
const router = express.Router();
const categoryController = require('./category.controller');

// Create a new category
router.post('/', categoryController.create);

// Get all categories
router.get('/', categoryController.findAll);

// Get single category
router.get('/:id', categoryController.findOne);

// Update category
router.put('/:id', categoryController.update);

// Delete category
router.delete('/:id', categoryController.delete);

module.exports = router; 
const Category = require('./category.model');

// Create category
exports.create = async (req, res) => {
    try {
        const category = new Category({
            name: req.body.name,
            description: req.body.description
        });

        const savedCategory = await category.save();
        res.status(201).json(savedCategory);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

// Get all categories
exports.findAll = async (req, res) => {
    try {
        const categories = await Category.find({ isDeleted: false });
        res.status(200).json(categories);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

// Get single category
exports.findOne = async (req, res) => {
    try {
        const category = await Category.findOne({ 
            _id: req.params.id,
            isDeleted: false 
        });
        
        if (!category) {
            return res.status(404).json({ message: 'Category not found' });
        }
        
        res.status(200).json(category);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

// Update category
exports.update = async (req, res) => {
    try {
        const category = await Category.findByIdAndUpdate(
            req.params.id,
            {
                name: req.body.name,
                description: req.body.description
            },
            { new: true }
        );

        if (!category) {
            return res.status(404).json({ message: 'Category not found' });
        }

        res.status(200).json(category);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

// Soft delete category
exports.delete = async (req, res) => {
    try {
        const category = await Category.findByIdAndUpdate(
            req.params.id,
            { isDeleted: true },
            { new: true }
        );

        if (!category) {
            return res.status(404).json({ message: 'Category not found' });
        }

        res.status(200).json({ message: 'Category deleted successfully' });
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
}; 
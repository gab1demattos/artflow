-- Categories with images
INSERT INTO Category (category_type, image)
VALUES (
        'Illustration & Digital Art',
        '/images/categories/digital.jpg'
    ),
    (
        'Graphic Design and Branding',
        '/images/categories/branding.jpg'
    ),
    (
        'Traditional Art & Painting',
        '/images/categories/monalisa.jpg'
    ),
    (
        '3D Art & Animation',
        '/images/categories/3d.jpg'
    ),
    (
        'Handmade & Craft Art',
        '/images/categories/craft.jpg'
    ),
    (
        'Body Art Design & Tattoo',
        '/images/categories/tattoo.jpg'
    );

-- Subcategories for each category
-- Illustration & Digital Art subcategories
INSERT INTO Subcategory (category_id, name)
VALUES (1, 'Character Design'),
    (1, 'Concept Art'),
    (1, 'Digital Painting'),
    (1, 'Vector Illustration'),
    (1, 'Pixel Art'),
    (1, 'Comic Book Art'),
    (1, 'Storyboard Art'),
    (1, 'Book Illustration');

-- Graphic Design and Branding subcategories
INSERT INTO Subcategory (category_id, name)
VALUES (2, 'Logo Design'),
    (2, 'Brand Identity'),
    (2, 'Packaging Design'),
    (2, 'Social Media Graphics'),
    (2, 'UI/UX Design'),
    (2, 'Print Design'),
    (2, 'Advertising Design'),
    (2, 'Typography');

-- Traditional Art & Painting subcategories
INSERT INTO Subcategory (category_id, name)
VALUES (3, 'Oil Painting'),
    (3, 'Watercolor'),
    (3, 'Acrylic Painting'),
    (3, 'Charcoal Drawing'),
    (3, 'Pencil Sketching'),
    (3, 'Pastel Art'),
    (3, 'Ink Drawing'),
    (3, 'Mixed Media');

-- 3D Art & Animation subcategories
INSERT INTO Subcategory (category_id, name)
VALUES (4, '3D Modeling'),
    (4, 'Character Animation'),
    (4, 'Motion Graphics'),
    (4, '3D Product Render'),
    (4, 'Architecture'),
    (4, 'Game Asset Creation'),
    (4, 'VFX & Simulation'),
    (4, 'AR/VR Content');

-- Handmade & Craft Art subcategories
INSERT INTO Subcategory (category_id, name)
VALUES (5, 'Pottery & Ceramics'),
    (5, 'Jewelry Making'),
    (5, 'Textile Art'),
    (5, 'Paper Crafts'),
    (5, 'Wood Carving'),
    (5, 'Sculpture'),
    (5, 'Macramé'),
    (5, 'Candle Making');

-- Body Art Design & Tattoo subcategories
INSERT INTO Subcategory (category_id, name)
VALUES (6, 'Tattoo Design'),
    (6, 'Henna Art'),
    (6, 'Body Painting'),
    (6, 'Temporary Tattoo'),
    (6, 'Flash Art'),
    (6, 'Custom Tattoo'),
    (6, 'Cover-up Design'),
    (6, 'Dotwork');
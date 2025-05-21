-- User data (mixture of regular users and admins)
INSERT INTO User (user_type, name, username, email, password, bio, profile_image) VALUES
('admin', 'Admin User', 'admin', 'admin@artflow.com', '701f81be760ef4fada8917640b07b398c855c854', NULL, NULL),  -- password: Teresa.mag17
('regular', 'John Doe', 'johndoe', 'john@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'Digital artist specializing in character design and concept art.', '/images/user_pfp/man.jpg'),
('regular', 'Jane Smith', 'janesmith', 'jane@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'Brand identity designer with 5+ years of experience working with startups.', '/images/user_pfp/woman.jpg'),
('regular', 'Michael Johnson', 'michaelj', 'michael@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'Traditional artist focusing on oil painting and portraits.', '/images/user_pfp/man.jpg'),
('regular', 'Sarah Williams', 'sarahw', 'sarah@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '3D modeling expert with background in game development.', '/images/user_pfp/woman.jpg'),
('regular', 'David Brown', 'davidb', 'david@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'Jewelry craftsman with a passion for unique handmade designs.', '/images/user_pfp/man.jpg'),
('regular', 'Emily Davis', 'emilyd', 'emily@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'Tattoo artist specializing in custom designs and cover-ups.', '/images/user_pfp/woman.jpg'),
('regular', 'Alex Thompson', 'alext', 'alex@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'Concept artist for video games and animation with 7 years of industry experience.', '/images/user_pfp/man.jpg'),
('regular', 'Teresa Magalhães', 'teresamag17','teresamag@example.com','701f81be760ef4fada8917640b07b398c855c854', 'Art director and digital illustrator passionate about storytelling through visuals.', '/images/user_pfp/woman.jpg'); -- password: Teresa.mag17

-- Categories with images
INSERT INTO Category (category_type, image) VALUES
('Illustration & Digital Art', '/images/categories/digital.jpg'),
('Graphic Design and Branding', '/images/categories/branding.jpg'),
('Traditional Art & Painting', '/images/categories/monalisa.jpg'),
('3D Art & Animation', '/images/categories/3d.jpg'),
('Handmade & Craft Art', '/images/categories/craft.jpg'),
('Body Art Design & Tattoo', '/images/categories/tattoo.jpg');

-- Subcategories for each category
-- Illustration & Digital Art subcategories
INSERT INTO Subcategory (category_id, name) VALUES
(1, 'Character Design'),
(1, 'Concept Art'),
(1, 'Digital Painting'),
(1, 'Vector Illustration'),
(1, 'Pixel Art'),
(1, 'Comic Book Art'),
(1, 'Storyboard Art'),
(1, 'Book Illustration');

-- Graphic Design and Branding subcategories
INSERT INTO Subcategory (category_id, name) VALUES
(2, 'Logo Design'),
(2, 'Brand Identity'),
(2, 'Packaging Design'),
(2, 'Social Media Graphics'),
(2, 'UI/UX Design'),
(2, 'Print Design'),
(2, 'Advertising Design'),
(2, 'Typography');

-- Traditional Art & Painting subcategories
INSERT INTO Subcategory (category_id, name) VALUES
(3, 'Oil Painting'),
(3, 'Watercolor'),
(3, 'Acrylic Painting'),
(3, 'Charcoal Drawing'),
(3, 'Pencil Sketching'),
(3, 'Pastel Art'),
(3, 'Ink Drawing'),
(3, 'Mixed Media');

-- 3D Art & Animation subcategories
INSERT INTO Subcategory (category_id, name) VALUES
(4, '3D Modeling'),
(4, 'Character Animation'),
(4, 'Motion Graphics'),
(4, '3D Product Rendering'),
(4, 'Architectural Visualization'),
(4, 'Game Asset Creation'),
(4, 'VFX & Simulation'),
(4, 'AR/VR Content');

-- Handmade & Craft Art subcategories
INSERT INTO Subcategory (category_id, name) VALUES
(5, 'Pottery & Ceramics'),
(5, 'Jewelry Making'),
(5, 'Textile Art'),
(5, 'Paper Crafts'),
(5, 'Wood Carving'),
(5, 'Sculpture'),
(5, 'Macramé & Fiber Arts'),
(5, 'Candle & Soap Making');

-- Body Art Design & Tattoo subcategories
INSERT INTO Subcategory (category_id, name) VALUES
(6, 'Tattoo Design'),
(6, 'Henna Art'),
(6, 'Body Painting'),
(6, 'Temporary Tattoo'),
(6, 'Flash Art'),
(6, 'Custom Tattoo Concepts'),
(6, 'Cover-up Design'),
(6, 'Blackwork & Dotwork');

-- Services (varied across users and categories)
INSERT INTO Service (user_id, title, description, category_id, price, delivery_time, images) VALUES
(2, 'Character Illustration', 'Custom character illustrations in various styles for your stories, games, or personal use.', 1, 75.00, 5, '../images/services/character_illustration.png'),
(3, 'Logo Design Package', 'Professional logo design including 3 concepts, unlimited revisions, and all file formats.', 2, 120.00, 7, '../images/services/logo_design_package.png'),
(4, 'Custom Oil Portrait', 'Handmade oil portrait from your photos. Perfect for gifts or personal collection.', 3, 250.00, 14, '../images/services/srv_682b442734f443.07923633_monalisa.jpg'),
(5, '3D Character Modeling', 'High-quality 3D character models ready for animation or gaming projects.', 4, 180.00, 10, '../images/services/srv_682b4427347d38.81134772_3d.jpg'),
(6, 'Handcrafted Jewelry', 'Unique handmade jewelry pieces customized to your preferences.', 5, 95.00, 8, '../images/services/srv_682b442734e0e2.96781324_craft.jpg'),
(7, 'Custom Tattoo Design', 'Original tattoo designs based on your ideas and preferences.', 6, 85.00, 6, '../images/services/srv_682c36f07ed799.00860090_Capture-2025-02-24-164426.png'),
(8, 'Digital Concept Art', 'Professional concept art for games, films, or personal projects.', 1, 150.00, 9, '../images/services/digital_concept_art.png'),
(2, 'Brand Identity Package', 'Complete brand identity design including logo, business cards, letterhead, and brand guidelines.', 2, 350.00, 21, '../images/services/brand_identity_package.jpg, ../images/services/color.jpeg, ../images/services/camaleao.jpeg, ../images/services/tree.jpeg, ../images/services/sun.jpeg');

-- Assign subcategories to services
INSERT INTO ServiceSubcategory (service_id, subcategory_id) VALUES
(1, 1), -- Character Illustration - Character Design
(1, 3), -- Character Illustration - Digital Painting
(2, 9), -- Logo Design Package - Logo Design
(2, 10), -- Logo Design Package - Brand Identity
(3, 17), -- Custom Oil Portrait - Oil Painting
(4, 25), -- 3D Character Modeling - 3D Modeling
(4, 26), -- 3D Character Modeling - Character Animation
(5, 34), -- Handcrafted Jewelry - Jewelry Making
(6, 41), -- Custom Tattoo Design - Tattoo Design
(6, 46), -- Custom Tattoo Design - Custom Tattoo Concepts
(7, 2), -- Digital Concept Art - Concept Art
(8, 9), -- Brand Identity Package - Logo Design
(8, 10), -- Brand Identity Package - Brand Identity
(8, 13); -- Brand Identity Package - Print Design

-- Exchanges
INSERT INTO Exchange (freelancer_id, client_id, service_id, status, amount) VALUES
(2, 5, 1, 'completed', 75.00),
(3, 6, 2, 'completed', 120.00),
(4, 7, 3, 'in progress', 250.00),
(5, 8, 4, 'in progress', 180.00),
(6, 2, 5, 'completed', 95.00),
(7, 3, 6, 'cancelled', 85.00);

-- Messages
INSERT INTO Message (sender_id, receiver_id, message) VALUES
(2, 5, 'Hello! I''ve started working on your character illustration.'),
(5, 2, 'Great! Looking forward to seeing the first draft.'),
(3, 6, 'Your logo design is ready for review. Please check your email.'),
(6, 3, 'Thank you! I just reviewed it and it looks amazing.'),
(4, 7, 'I need some additional details for your oil portrait.'),
(7, 4, 'Sure, what information do you need?');

-- Reviews
INSERT INTO Review (user_id, service_id, rating, comment) VALUES
(5, 1, 5, 'Excellent work! The character illustration exceeded my expectations.'),
(6, 2, 4, 'Great logo design. Professional and responsive service.'),
(2, 5, 5, 'Beautiful handcrafted jewelry. Will definitely order again!'),
(3, 8, 3, 'Good design but could have used more detail in some areas.'),
(2, 8, 5, 'Excellent work! The character illustration exceeded my expectations.'),
(1, 8, 4, 'Great logo design. Professional and responsive service.'),
(6, 8, 5, 'Beautiful handcrafted jewelry. Will definitely order again!'),
(4, 8, 3, 'Good design but could have used more detail in some areas.');
-- Assign subcategories to services
INSERT INTO ServiceSubcategory (service_id, subcategory_id)
VALUES (1, 1),
    (1, 3),
    (2, 9),
    (2, 10),
    (3, 17),
    (4, 25),
    (4, 26),
    (5, 34),
    (6, 41),
    (6, 46),
    (7, 2),
    (8, 9),
    (8, 10),
    (8, 13),
    (9, 8),
    (10, 11),
    (11, 18),
    (12, 27),
    (13, 39),
    (14, 42),
    (15, 5),
    (16, 47),
    (17, 7),
    (18, 13),
    (19, 24),
    (20, 28),
    (21, 36),
    (22, 47),
    (23, 8),
    (24, 16),
    (25, 20),
    (26, 32),
    (27, 40),
    (28, 44),
    (29, 29),
    (30, 48),
    (31, 7),
    (32, 15),
    (33, 22),
    (34, 27),
    (35, 39),
    (36, 42),
    (37, 5),
    (38, 13),
    (39, 24),
    (40, 28),
    (41, 36),
    (42, 47),
    (43, 8),
    (44, 16),
    (45, 20),
    (46, 32),
    (47, 40),
    (48, 44),
    (49, 1),
    (49, 3),
    (50, 2),
    (51, 6),
    (52, 3),
    (53, 4),
    (54, 8),
    (55, 5),
    (56, 6),
    (57, 3),
    (58, 8),
    (59, 7),
    (60, 1),
    (61, 2),
    (62, 8),
    (63, 3),
    (64, 2),
    (65, 5),
    (66, 6),
    (67, 7),
    (68, 4),
    (69, 4),
    (70, 8),
    (71, 1),
    (72, 3),
    (73, 4),
    (74, 8),
    (75, 3),
    (76, 4),
    (77, 1),
    (78, 3),
    (79, 4),
    (80, 5),
    -- Enchanted Illustration Series
    (81, 3), -- Digital Painting
    (81, 6), -- Comic Book Art
    (81, 8), -- Book Illustration
    
    -- Modern Corporate Branding Package
    (82, 9), -- Logo Design
    (82, 10), -- Brand Identity
    (82, 13), -- Print Design
    
    -- Artisanal Pottery Collection
    (83, 33), -- Pottery & Ceramics
    (83, 38), -- Sculpture
    
    -- Character Design Portfolio
    (84, 1), -- Character Design
    (84, 2), -- Concept Art
    (84, 3), -- Digital Painting
    
    -- Digital Marketing Asset Package
    (85, 11), -- Packaging Design
    (85, 12), -- Social Media Graphics
    (85, 14), -- Advertising Design
    
    -- Handwoven Textile Art
    (86, 35), -- Textile Art
    (86, 39), -- Macramé & Fiber Arts
    
    -- Fantasy Environment Design
    (87, 2), -- Concept Art
    (87, 3), -- Digital Painting
    
    -- Brand Identity Evolution Package
    (88, 10), -- Brand Identity
    (88, 13), -- Print Design
    (88, 16), -- Typography
    
    -- Sustainable Craft Workshop
    (89, 33), -- Pottery & Ceramics
    (89, 36), -- Paper Crafts
    (89, 40), -- Candle & Soap Making
    
    -- Tribal Tattoo Designs
    (90, 41), -- Tattoo Design
    (90, 45), -- Flash Art
    (90, 46), -- Custom Tattoo Concepts
    
    -- Minimalist Tattoo Art
    (91, 41), -- Tattoo Design
    (91, 46), -- Custom Tattoo Concepts
    
    -- Watercolor Style Tattoos
    (92, 41), -- Tattoo Design
    (92, 46), -- Custom Tattoo Concepts
    
    -- Portrait Tattoo Design
    (93, 41), -- Tattoo Design
    (93, 46), -- Custom Tattoo Concepts
    
    -- Festival Body Art Package
    (94, 43), -- Body Painting
    (94, 44), -- Temporary Tattoo
    
    -- Traditional Japanese Tattoo
    (95, 41), -- Tattoo Design
    (95, 45), -- Flash Art
    (95, 46), -- Custom Tattoo Concepts
    
    -- Dotwork Mandala Tattoos
    (96, 41), -- Tattoo Design
    (96, 48), -- Blackwork & Dotwork
    (96, 46), -- Custom Tattoo Concepts
    
    -- Bridal Henna Package
    (97, 42), -- Henna Art
    (97, 44), -- Temporary Tattoo
    (97, 45), -- Flash Art
    
    -- Geometric Blackwork Design
    (98, 41), -- Tattoo Design
    (98, 48), -- Blackwork & Dotwork
    (98, 46), -- Custom Tattoo Concepts
    
    -- Couple Matching Tattoos
    (99, 41), -- Tattoo Design
    (99, 46), -- Custom Tattoo Concepts
    (99, 45), -- Flash Art
    
    -- Festival Face Art
    (100, 43), -- Body Painting
    (100, 44), -- Temporary Tattoo
    (100, 42), -- Henna Art
    
    -- Sacred Geometry Tattoo
    (101, 41), -- Tattoo Design
    (101, 48), -- Blackwork & Dotwork
    (101, 46), -- Custom Tattoo Concepts
    
    -- Professional Henna Training
    (102, 42), -- Henna Art
    (102, 44), -- Temporary Tattoo
    (102, 45), -- Flash Art
    
    -- Vector Portrait Collection
    (103, 4), -- Vector Illustration
    (103, 3), -- Digital Painting
    
    -- Game Character Concept Package
    (104, 1), -- Character Design
    (104, 2), -- Concept Art
    (104, 3), -- Digital Painting
    
    -- Comic Page Creation
    (105, 6), -- Comic Book Art
    (105, 1), -- Character Design
    (105, 3), -- Digital Painting
    
    -- Retro Pixel Art Scenes
    (106, 5), -- Pixel Art
    (106, 2), -- Concept Art
    
    -- Film Storyboard Package
    (107, 7), -- Storyboard Art
    (107, 2), -- Concept Art
    
    -- Children's Book Package
    (108, 8), -- Book Illustration
    (108, 1), -- Character Design
    (108, 3), -- Digital Painting
    
    -- Architectural Walkthrough
    (109, 25), -- 3D Modeling
    (109, 29), -- Architectural Visualization
    (109, 27), -- Motion Graphics
    
    -- Character Animation Pack
    (110, 26), -- Character Animation
    (110, 25), -- 3D Modeling
    (110, 30), -- Game Asset Creation
    
    -- Product Launch Animation
    (111, 28), -- 3D Product Rendering
    (111, 27), -- Motion Graphics
    
    -- Game Environment Assets
    (112, 25), -- 3D Modeling
    (112, 30), -- Game Asset Creation
    
    -- VR Experience Design
    (113, 32), -- AR/VR Content
    (113, 25), -- 3D Modeling
    (113, 31), -- VFX & Simulation
    
    -- Product Configurator 3D
    (114, 28), -- 3D Product Rendering
    (114, 32); -- AR/VR Content

-- Adding additional subcategories to existing services to ensure coverage
INSERT INTO ServiceSubcategory (service_id, subcategory_id)
VALUES
    -- Add Henna Art subcategory to Body Painting Service
    (34, 42), -- Henna Art
    
    -- Add Blackwork & Dotwork to existing Blackwork Tattoo service
    (42, 48), -- Blackwork & Dotwork
    
    -- Add Flash Art to existing Custom Tattoo Design
    (6, 45), -- Flash Art
    
    -- Add Cover-up Design to Watercolor Style Tattoos
    (92, 47); -- Cover-up Design
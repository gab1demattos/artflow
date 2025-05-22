-- User data (mixture of regular users and admins)
INSERT INTO User (
        user_type,
        name,
        username,
        email,
        password,
        bio,
        profile_image
    )
VALUES (
        'admin',
        'Admin User',
        'admin',
        'admin@artflow.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        NULL,
        NULL
    ),
    -- password: Teresa.mag17
    (
        'regular',
        'John Doe',
        'johndoe',
        'john@example.com',
        '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W',
        'Digital artist specializing in character design and concept art.',
        '/images/user_pfp/man.jpg'
    ),
    (
        'regular',
        'Jane Smith',
        'janesmith',
        'jane@example.com',
        '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W',
        'Brand identity designer with 5+ years of experience working with startups.',
        '/images/user_pfp/woman.jpg'
    ),
    (
        'regular',
        'Michael Johnson',
        'michaelj',
        'michael@example.com',
        '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W',
        'Traditional artist focusing on oil painting and portraits.',
        '/images/user_pfp/man.jpg'
    ),
    (
        'regular',
        'Sarah Williams',
        'sarahw',
        'sarah@example.com',
        '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W',
        '3D modeling expert with background in game development.',
        '/images/user_pfp/woman.jpg'
    ),
    (
        'regular',
        'David Brown',
        'davidb',
        'david@example.com',
        '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W',
        'Jewelry craftsman with a passion for unique handmade designs.',
        '/images/user_pfp/man.jpg'
    ),
    (
        'regular',
        'Emily Dickinson',
        'emilyd',
        'emily@example.com',
        '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W',
        'Tattoo artist specializing in custom designs and cover-ups.',
        '/images/user_pfp/woman.jpg'
    ),
    (
        'regular',
        'Alex Thompson',
        'alext',
        'alex@example.com',
        '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W',
        'Concept artist for video games and animation with 7 years of industry experience.',
        '/images/user_pfp/man.jpg'
    ),
    (
        'regular',
        'Teresa Magalhães',
        'teresamag17',
        'teresamag@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Art director and digital illustrator passionate about storytelling through visuals.',
        '/images/user_pfp/woman.jpg'
    );
-- password: Teresa.mag17
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
    (4, '3D Product Rendering'),
    (4, 'Architectural Visualization'),
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
    (5, 'Macramé & Fiber Arts'),
    (5, 'Candle & Soap Making');
-- Body Art Design & Tattoo subcategories
INSERT INTO Subcategory (category_id, name)
VALUES (6, 'Tattoo Design'),
    (6, 'Henna Art'),
    (6, 'Body Painting'),
    (6, 'Temporary Tattoo'),
    (6, 'Flash Art'),
    (6, 'Custom Tattoo Concepts'),
    (6, 'Cover-up Design'),
    (6, 'Blackwork & Dotwork');
-- Services (varied across users and categories)
INSERT INTO Service (
        user_id,
        title,
        description,
        category_id,
        price,
        delivery_time,
        images
    )
VALUES (
        2,
        'Character Illustration',
        'Custom character illustrations in various styles for your stories, games, or personal use.',
        1,
        75.00,
        5,
        '/images/services/character_illustration.png'
    ),
    (
        3,
        'Logo Design Package',
        'Professional logo design including 3 concepts, unlimited revisions, and all file formats.',
        2,
        120.00,
        7,
        '/images/services/logo_design_package.png'
    ),
    (
        4,
        'Custom Oil Portrait',
        'Handmade oil portrait from your photos. Perfect for gifts or personal collection.',
        3,
        250.00,
        14,
        '/images/services/srv_682b442734f443.07923633_monalisa.jpg'
    ),
    (
        5,
        '3D Character Modeling',
        'High-quality 3D character models ready for animation or gaming projects.',
        4,
        180.00,
        10,
        '/images/services/srv_682b4427347d38.81134772_3d.jpg'
    ),
    (
        6,
        'Handcrafted Jewelry',
        'Unique handmade jewelry pieces customized to your preferences.',
        5,
        95.00,
        8,
        '/images/services/srv_682b442734e0e2.96781324_craft.jpg'
    ),
    (
        7,
        'Custom Tattoo Design',
        'Original tattoo designs based on your ideas and preferences.',
        6,
        85.00,
        6,
        '/images/services/srv_682c36f07ed799.00860090_Capture-2025-02-24-164426.png'
    ),
    (
        8,
        'Digital Concept Art',
        'Professional concept art for games, films, or personal projects.',
        1,
        150.00,
        9,
        '/images/services/digital_concept_art.png'
    ),
    (
        2,
        'Brand Identity Package',
        'Complete brand identity design including logo, business cards, letterhead, and brand guidelines.',
        2,
        350.00,
        21,
        '/images/services/brand_identity_package.jpg'
    ),
    (
        2,
        'Children Book Illustration',
        'Whimsical and colorful illustrations for children''s books and educational materials.',
        1,
        90.00,
        7,
        '/images/services/character_illustration.png'
    ),
    (
        3,
        'Business Card Design',
        'Custom business card design to make your brand stand out.',
        2,
        40.00,
        3,
        '/images/services/logo_design_package.png'
    ),
    (
        4,
        'Pet Portrait in Oil',
        'Hand-painted oil portraits of your beloved pets.',
        3,
        180.00,
        10,
        '/images/services/srv_682b442734f443.07923633_monalisa.jpg'
    ),
    (
        5,
        '3D Product Visualization',
        'Realistic 3D renders for product marketing and presentations.',
        4,
        220.00,
        12,
        '/images/services/srv_682b4427347d38.81134772_3d.jpg'
    ),
    (
        6,
        'Custom Ceramic Vase',
        'Handmade ceramic vases with unique designs.',
        5,
        60.00,
        6,
        '/images/services/srv_682b442734e0e2.96781324_craft.jpg'
    ),
    (
        7,
        'Henna Body Art',
        'Beautiful and intricate henna designs for special occasions.',
        6,
        55.00,
        2,
        '/images/services/srv_682c36f07ed799.00860090_Capture-2025-02-24-164426.png'
    ),
    (
        8,
        'Environment Concept Art',
        'Detailed environment concept art for games and films.',
        1,
        170.00,
        8,
        '/images/services/digital_concept_art.png'
    ),
    (
        2,
        'Social Media Graphics',
        'Eye-catching graphics for your social media campaigns.',
        2,
        60.00,
        4,
        '/images/services/brand_identity_package.jpg'
    ),
    (
        3,
        'Watercolor Portrait',
        'Delicate watercolor portraits from your photos.',
        3,
        130.00,
        9,
        '/images/services/srv_682b442734f443.07923633_monalisa.jpg'
    ),
    (
        4,
        'Game Asset Creation',
        '3D models and textures for your game projects.',
        4,
        200.00,
        11,
        '/images/services/srv_682b4427347d38.81134772_3d.jpg'
    ),
    (
        5,
        'Macramé Wall Hanging',
        'Handcrafted macramé wall hangings for home decor.',
        5,
        45.00,
        5,
        '/images/services/srv_682b442734e0e2.96781324_craft.jpg'
    ),
    (
        6,
        'Flash Tattoo Sheet',
        'A set of original flash tattoo designs.',
        6,
        70.00,
        3,
        '/images/services/srv_682c36f07ed799.00860090_Capture-2025-02-24-164426.png'
    ),
    (
        7,
        'Pixel Art Sprite Sheet',
        'Custom pixel art sprites for games and apps.',
        1,
        80.00,
        6,
        '/images/services/digital_concept_art.png'
    ),
    (
        8,
        'Packaging Design',
        'Creative packaging design for your products.',
        2,
        110.00,
        7,
        '/images/services/brand_identity_package.jpg'
    ),
    (
        2,
        'Acrylic Landscape Painting',
        'Vibrant acrylic paintings of landscapes and nature.',
        3,
        160.00,
        12,
        '/images/services/srv_682b442734f443.07923633_monalisa.jpg'
    ),
    (
        3,
        'Motion Graphics Animation',
        'Short motion graphics animations for ads and intros.',
        4,
        140.00,
        8,
        '/images/services/srv_682b4427347d38.81134772_3d.jpg'
    ),
    (
        4,
        'Handmade Soap Set',
        'Natural handmade soaps in a variety of scents.',
        5,
        35.00,
        4,
        '/images/services/srv_682b442734e0e2.96781324_craft.jpg'
    ),
    (
        5,
        'Temporary Tattoo Design',
        'Temporary tattoo designs for events and parties.',
        6,
        50.00,
        2,
        '/images/services/srv_682c36f07ed799.00860090_Capture-2025-02-24-164426.png'
    ),
    (
        6,
        'Book Cover Illustration',
        'Custom illustrations for book covers and editorial projects.',
        1,
        120.00,
        7,
        '/images/services/character_illustration.png'
    ),
    (
        7,
        'Typography Poster',
        'Unique typographic poster designs for your space.',
        2,
        65.00,
        5,
        '/images/services/logo_design_package.png'
    ),
    (
        8,
        'Mixed Media Portrait',
        'Portraits using a mix of traditional and digital techniques.',
        3,
        200.00,
        13,
        '/images/services/srv_682b442734f443.07923633_monalisa.jpg'
    ),
    (
        2,
        'AR/VR Content Creation',
        'Augmented and virtual reality content for marketing and education.',
        4,
        300.00,
        15,
        '/images/services/srv_682b4427347d38.81134772_3d.jpg'
    ),
    (
        3,
        'Wood Carving Sculpture',
        'Custom wood carvings for gifts and decor.',
        5,
        85.00,
        9,
        '/images/services/srv_682b442734e0e2.96781324_craft.jpg'
    ),
    (
        4,
        'Body Painting Session',
        'Creative body painting for events and photoshoots.',
        6,
        100.00,
        3,
        '/images/services/srv_682c36f07ed799.00860090_Capture-2025-02-24-164426.png'
    ),
    (
        6,
        'Pottery Workshop',
        'Learn pottery techniques and create your own ceramic pieces in a hands-on workshop.',
        5,
        70.00,
        5,
        '/images/services/srv_682b442734e0e2.96781324_craft.jpg'
    ),
    (
        7,
        'Blackwork Tattoo',
        'Bold blackwork tattoo designs tailored to your style.',
        6,
        90.00,
        4,
        '/images/services/srv_682c36f07ed799.00860090_Capture-2025-02-24-164426.png'
    ),
    (
        8,
        'Storyboard Art',
        'Professional storyboard art for film, animation, or advertising.',
        1,
        100.00,
        6,
        '/images/services/digital_concept_art.png'
    ),
    (
        2,
        'Advertising Poster Design',
        'Eye-catching advertising posters for your business or event.',
        2,
        80.00,
        5,
        '/images/services/brand_identity_package.jpg'
    ),
    (
        3,
        'Pastel Portrait',
        'Soft pastel portraits with a unique artistic touch.',
        3,
        140.00,
        8,
        '/images/services/srv_682b442734f443.07923633_monalisa.jpg'
    ),
    (
        4,
        'Motion Graphics for Social Media',
        'Animated graphics for your social media campaigns.',
        4,
        110.00,
        7,
        '/images/services/srv_682b4427347d38.81134772_3d.jpg'
    ),
    (
        5,
        'Macramé Plant Hanger',
        'Handmade macramé plant hangers for home decor.',
        5,
        30.00,
        3,
        '/images/services/srv_682b442734e0e2.96781324_craft.jpg'
    ),
    (
        6,
        'Henna Bridal Art',
        'Intricate henna designs for weddings and special occasions.',
        6,
        120.00,
        2,
        '/images/services/srv_682c36f07ed799.00860090_Capture-2025-02-24-164426.png'
    ),
    (
        7,
        'Pixel Art Animation',
        'Animated pixel art for games and digital media.',
        1,
        95.00,
        7,
        '/images/services/digital_concept_art.png'
    ),
    (
        8,
        'UI/UX App Design',
        'Modern and user-friendly UI/UX design for mobile apps.',
        2,
        200.00,
        10,
        '/images/services/brand_identity_package.jpg'
    ),
    (
        2,
        'Ink Drawing Commission',
        'Detailed ink drawings for personal or commercial use.',
        3,
        110.00,
        6,
        '/images/services/srv_682b442734f443.07923633_monalisa.jpg'
    ),
    (
        3,
        '3D Product Animation',
        'Animated 3D product showcases for marketing.',
        4,
        210.00,
        13,
        '/images/services/srv_682b4427347d38.81134772_3d.jpg'
    ),
    (
        4,
        'Paper Craft Workshop',
        'Creative paper craft workshops for all ages.',
        5,
        50.00,
        2,
        '/images/services/srv_682b442734e0e2.96781324_craft.jpg'
    ),
    (
        5,
        'Cover-up Tattoo Design',
        'Custom cover-up tattoo designs to transform old tattoos.',
        6,
        130.00,
        5,
        '/images/services/srv_682c36f07ed799.00860090_Capture-2025-02-24-164426.png'
    ),
    (
        6,
        'Book Illustration',
        'Illustrations for books, magazines, and editorial projects.',
        1,
        125.00,
        8,
        '/images/services/character_illustration.png'
    ),
    (
        7,
        'Typography Logo',
        'Custom typography-based logo designs.',
        2,
        75.00,
        4,
        '/images/services/logo_design_package.png'
    ),
    (
        8,
        'Charcoal Drawing',
        'Expressive charcoal drawings for portraits or landscapes.',
        3,
        105.00,
        7,
        '/images/services/srv_682b442734f443.07923633_monalisa.jpg'
    ),
    (
        2,
        'VFX Simulation',
        'Visual effects and simulation for video and animation.',
        4,
        260.00,
        14,
        '/images/services/srv_682b4427347d38.81134772_3d.jpg'
    ),
    (
        3,
        'Candle Making Kit',
        'DIY candle making kits with all materials included.',
        5,
        40.00,
        3,
        '/images/services/srv_682b442734e0e2.96781324_craft.jpg'
    ),
    (
        4,
        'Temporary Body Art',
        'Temporary body art for festivals and events.',
        6,
        60.00,
        2,
        '/images/services/srv_682c36f07ed799.00860090_Capture-2025-02-24-164426.png'
    ),
    (
        2,
        'Anime Style Portrait',
        'Personalized anime-style portraits from your photos or ideas.',
        1,
        60.00,
        4,
        '/images/services/character_illustration.png'
    ),
    (
        3,
        'Fantasy Creature Design',
        'Unique fantasy creature designs for games, books, or personal projects.',
        1,
        90.00,
        6,
        '/images/services/digital_concept_art.png'
    ),
    (
        4,
        'Webtoon Comic Art',
        'Webtoon-style comic panels and full episodes, ready for publishing.',
        1,
        120.00,
        10,
        '/images/services/digital_concept_art.png'
    ),
    (
        5,
        'Digital Pet Portrait',
        'Cute and vibrant digital portraits of your pets.',
        1,
        55.00,
        3,
        '/images/services/character_illustration.png'
    ),
    (
        6,
        'Vector Logo Mascot',
        'Custom vector mascot illustrations for branding and marketing.',
        1,
        80.00,
        5,
        '/images/services/character_illustration.png'
    ),
    (
        7,
        'Children’s Book Cover',
        'Colorful and engaging book cover illustrations for children’s literature.',
        1,
        100.00,
        7,
        '/images/services/character_illustration.png'
    ),
    (
        8,
        'Pixel Art Game Assets',
        'Pixel art tilesets, characters, and UI for indie games.',
        1,
        70.00,
        6,
        '/images/services/digital_concept_art.png'
    ),
    (
        2,
        'Comic Strip Creation',
        'Short comic strips for web, print, or social media.',
        1,
        65.00,
        4,
        '/images/services/digital_concept_art.png'
    ),
    (
        3,
        'Digital Painting Commission',
        'Detailed digital paintings for gifts, decor, or personal use.',
        1,
        110.00,
        8,
        '/images/services/character_illustration.png'
    ),
    (
        4,
        'Book Illustration Bundle',
        'Multiple interior illustrations for books or magazines.',
        1,
        200.00,
        14,
        '/images/services/character_illustration.png'
    ),
    (
        5,
        'Storyboard for Animation',
        'Professional storyboards for animation or film projects.',
        1,
        95.00,
        5,
        '/images/services/digital_concept_art.png'
    ),
    (
        6,
        'Avatar/Icon Design',
        'Custom digital avatars or icons for social media and games.',
        1,
        40.00,
        2,
        '/images/services/character_illustration.png'
    ),
    (
        7,
        'Concept Art for Environments',
        'Atmospheric environment concept art for games and movies.',
        1,
        130.00,
        9,
        '/images/services/digital_concept_art.png'
    ),
    (
        8,
        'Book Character Illustration',
        'Illustrations of book characters in your preferred style.',
        1,
        85.00,
        6,
        '/images/services/character_illustration.png'
    ),
    (
        2,
        'Digital Art Poster',
        'High-resolution digital art posters for print or digital use.',
        1,
        75.00,
        4,
        '/images/services/digital_concept_art.png'
    ),
    (
        3,
        'Fantasy Map Illustration',
        'Hand-drawn style fantasy maps for novels or games.',
        1,
        150.00,
        12,
        '/images/services/digital_concept_art.png'
    ),
    (
        4,
        'Pixel Art Portrait',
        'Retro pixel art portraits for avatars or gifts.',
        1,
        50.00,
        3,
        '/images/services/digital_concept_art.png'
    ),
    (
        5,
        'Comic Book Cover',
        'Dynamic comic book cover illustrations.',
        1,
        110.00,
        7,
        '/images/services/digital_concept_art.png'
    ),
    (
        6,
        'Storyboard Animatic',
        'Animated storyboards (animatics) for pre-visualization.',
        1,
        140.00,
        10,
        '/images/services/digital_concept_art.png'
    ),
    (
        7,
        'Digital Sticker Pack',
        'Custom digital stickers for messaging apps.',
        1,
        35.00,
        2,
        '/images/services/character_illustration.png'
    ),
    (
        8,
        'Vector Illustration Set',
        'A set of vector illustrations for web or print.',
        1,
        90.00,
        6,
        '/images/services/character_illustration.png'
    ),
    (
        2,
        'Book Illustration (B&W)',
        'Black and white illustrations for books and zines.',
        1,
        60.00,
        5,
        '/images/services/character_illustration.png'
    ),
    (
        3,
        'Chibi Character Art',
        'Cute chibi-style character illustrations.',
        1,
        45.00,
        3,
        '/images/services/character_illustration.png'
    ),
    (
        4,
        'Digital Art Timelapse',
        'Timelapse video of your digital art commission process.',
        1,
        30.00,
        2,
        '/images/services/digital_concept_art.png'
    ),
    (
        5,
        'Game UI Illustration',
        'Custom UI elements and icons for games.',
        1,
        100.00,
        8,
        '/images/services/digital_concept_art.png'
    ),
    (
        6,
        'Book Illustration (Color)',
        'Full-color book illustrations for children’s or adult books.',
        1,
        130.00,
        10,
        '/images/services/character_illustration.png'
    ),
    (
        7,
        'Digital Art for Merch',
        'Artwork for t-shirts, mugs, and other merchandise.',
        1,
        80.00,
        5,
        '/images/services/digital_concept_art.png'
    ),
    (
        8,
        'Web Banner Illustration',
        'Custom illustrations for website banners.',
        1,
        70.00,
        4,
        '/images/services/digital_concept_art.png'
    ),
    (
        2,
        'Anime Style Portrait',
        'Personalized anime-style digital portraits for profile pictures or gifts.',
        1,
        60.00,
        4,
        '/images/services/digital_concept_art.png'
    ),
    (
        3,
        'Fantasy Creature Illustration',
        'Detailed illustrations of fantasy creatures for games, books, or personal use.',
        1,
        95.00,
        6,
        '/images/services/character_illustration.png'
    ),
    (
        4,
        'Comic Book Page Art',
        'Full comic book page layouts with dynamic characters and backgrounds.',
        1,
        150.00,
        10,
        '/images/services/digital_concept_art.png'
    ),
    (
        5,
        'Digital Pet Portrait',
        'Cute and vibrant digital portraits of your pets.',
        1,
        55.00,
        3,
        '/images/services/character_illustration.png'
    ),
    (
        6,
        'Custom Twitch Emotes',
        'Unique emotes for Twitch streamers, delivered in all required sizes.',
        1,
        40.00,
        2,
        '/images/services/digital_concept_art.png'
    ),
    (
        7,
        'Book Cover Digital Art',
        'Eye-catching digital art for book covers, eBooks, and print.',
        1,
        110.00,
        7,
        '/images/services/character_illustration.png'
    ),
    (
        8,
        'Game Character Concept',
        'Original character concepts for indie or AAA games.',
        1,
        130.00,
        8,
        '/images/services/character_illustration.png'
    ),
    (
        2,
        'Vector Mascot Design',
        'Crisp vector mascots for branding, sports teams, or events.',
        1,
        85.00,
        5,
        '/images/services/digital_concept_art.png'
    ),
    (
        3,
        'Children’s Book Character Art',
        'Whimsical character illustrations for children’s books.',
        1,
        90.00,
        6,
        '/images/services/character_illustration.png'
    ),
    (
        4,
        'Digital Storyboard Panels',
        'Professional storyboard panels for animation or film.',
        1,
        100.00,
        5,
        '/images/services/digital_concept_art.png'
    ),
    (
        5,
        'Pixel Art Game Assets',
        'Retro-style pixel art assets for indie games.',
        1,
        70.00,
        4,
        '/images/services/digital_concept_art.png'
    ),
    (
        6,
        'Social Media Avatar Illustration',
        'Custom digital avatars for social media and branding.',
        1,
        50.00,
        2,
        '/images/services/character_illustration.png'
    ),
    (
        7,
        'Fantasy Map Illustration',
        'Hand-drawn style fantasy maps for novels or games.',
        1,
        120.00,
        9,
        '/images/services/digital_concept_art.png'
    ),
    (
        8,
        'Digital Painting Commission',
        'High-resolution digital paintings for personal or commercial use.',
        1,
        140.00,
        8,
        '/images/services/character_illustration.png'
    ),
    (
        2,
        'Comic Strip Creation',
        'Short comic strips with original characters and humor.',
        1,
        80.00,
        4,
        '/images/services/digital_concept_art.png'
    ),
    (
        3,
        'Book Illustration (Full Page)',
        'Full-page digital illustrations for books and magazines.',
        1,
        125.00,
        7,
        '/images/services/character_illustration.png'
    ),
    (
        4,
        'Digital Art for Merch',
        'Artwork for t-shirts, stickers, and other merchandise.',
        1,
        90.00,
        5,
        '/images/services/digital_concept_art.png'
    ),
    (
        5,
        'Chibi Character Art',
        'Cute chibi-style character illustrations.',
        1,
        45.00,
        2,
        '/images/services/character_illustration.png'
    ),
    (
        6,
        'Isometric Game Art',
        'Isometric digital art for games and apps.',
        1,
        110.00,
        6,
        '/images/services/digital_concept_art.png'
    ),
    (
        7,
        'Digital Portrait (Realistic)',
        'Realistic digital portraits from your photos.',
        1,
        100.00,
        5,
        '/images/services/character_illustration.png'
    ),
    (
        8,
        'Webtoon Character Design',
        'Character design for webtoons and online comics.',
        1,
        85.00,
        4,
        '/images/services/digital_concept_art.png'
    ),
    (
        2,
        'Digital Art NFT Creation',
        'Unique digital art pieces for NFT projects.',
        1,
        150.00,
        7,
        '/images/services/character_illustration.png'
    ),
    (
        3,
        'Digital Art Print Ready',
        'Digital art formatted and prepared for high-quality printing.',
        1,
        95.00,
        3,
        '/images/services/digital_concept_art.png'
    ),
    (
        4,
        'Custom Twitch Overlay Art',
        'Custom overlay art for Twitch and YouTube streams.',
        1,
        70.00,
        3,
        '/images/services/digital_concept_art.png'
    ),
    (
        5,
        'Digital Sticker Pack',
        'A pack of digital stickers for messaging apps.',
        1,
        60.00,
        2,
        '/images/services/character_illustration.png'
    ),
    (
        6,
        'Digital Art for Album Covers',
        'Striking digital art for music album covers.',
        1,
        110.00,
        6,
        '/images/services/digital_concept_art.png'
    ),
    (
        7,
        'Digital Art for Posters',
        'Poster-ready digital art for events or decor.',
        1,
        100.00,
        5,
        '/images/services/character_illustration.png'
    ),
    (
        8,
        'Digital Art for YouTube Thumbnails',
        'Custom digital art for eye-catching YouTube thumbnails.',
        1,
        55.00,
        2,
        '/images/services/digital_concept_art.png'
    ),
    (
        2,
        'Cyberpunk Character Portrait',
        'Futuristic cyberpunk-style character illustration with neon colors and tech details.',
        1,
        85.00,
        5,
        '/images/services/character_illustration.png'
    ),
    (
        2,
        'Fantasy Animal Companion',
        'Custom fantasy animal companion illustrations for stories or games.',
        1,
        70.00,
        4,
        '/images/services/character_illustration.png'
    ),
    (
        2,
        'Digital Art for Album Covers',
        'Unique digital art for music album covers in any genre.',
        1,
        120.00,
        7,
        '/images/services/digital_concept_art.png'
    ),
    (
        2,
        'Comic Book Character Sheet',
        'Detailed character sheets for comic book or animation projects.',
        1,
        95.00,
        6,
        '/images/services/character_illustration.png'
    ),
    (
        2,
        'Digital Art for Social Media',
        'Eye-catching digital illustrations for Instagram, Twitter, and more.',
        1,
        60.00,
        3,
        '/images/services/digital_concept_art.png'
    ),
    (
        2,
        'Chibi Couple Portrait',
        'Cute chibi-style couple portraits, perfect for gifts.',
        1,
        55.00,
        3,
        '/images/services/character_illustration.png'
    ),
    (
        2,
        'Digital Art for Twitch Panels',
        'Custom digital art for Twitch panels and overlays.',
        1,
        75.00,
        4,
        '/images/services/digital_concept_art.png'
    ),
    (
        2,
        'Book Illustration (Children)',
        'Colorful and whimsical illustrations for children’s books.',
        1,
        100.00,
        7,
        '/images/services/character_illustration.png'
    ),
    (
        2,
        'Digital Art for Stickers',
        'Fun and vibrant digital art for sticker packs.',
        1,
        50.00,
        2,
        '/images/services/digital_concept_art.png'
    ),
    (
        2,
        'Fantasy Weapon Design',
        'Original fantasy weapon illustrations for games or books.',
        1,
        80.00,
        5,
        '/images/services/digital_concept_art.png'
    );
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
    -- Anime Style Portrait: Character Design
    (49, 3),
    (50, 2),
    -- Fantasy Creature Design: Concept Art
    (51, 6),
    -- Webtoon Comic Art: Comic Book Art
    (52, 3),
    -- Digital Pet Portrait: Digital Painting
    (53, 4),
    -- Vector Logo Mascot: Vector Illustration
    (54, 8),
    -- Children’s Book Cover: Book Illustration
    (55, 5),
    -- Pixel Art Game Assets: Pixel Art
    (56, 6),
    -- Comic Strip Creation: Comic Book Art
    (57, 3),
    -- Digital Painting Commission: Digital Painting
    (58, 8),
    -- Book Illustration Bundle: Book Illustration
    (59, 7),
    -- Storyboard for Animation: Storyboard Art
    (60, 1),
    -- Avatar/Icon Design: Character Design
    (61, 2),
    -- Concept Art for Environments: Concept Art
    (62, 8),
    -- Book Character Illustration: Book Illustration
    (63, 3),
    -- Digital Art Poster: Digital Painting
    (64, 2),
    -- Fantasy Map Illustration: Concept Art
    (65, 5),
    -- Pixel Art Portrait: Pixel Art
    (66, 6),
    -- Comic Book Cover: Comic Book Art
    (67, 7),
    -- Storyboard Animatic: Storyboard Art
    (68, 4),
    -- Digital Sticker Pack: Vector Illustration
    (69, 4),
    -- Vector Illustration Set: Vector Illustration
    (70, 8),
    -- Book Illustration (B&W): Book Illustration
    (71, 1),
    -- Chibi Character Art: Character Design
    (72, 3),
    -- Digital Art Timelapse: Digital Painting
    (73, 4),
    -- Game UI Illustration: Vector Illustration
    (74, 8),
    -- Book Illustration (Color): Book Illustration
    (75, 3),
    -- Digital Art for Merch: Digital Painting
    (76, 4),
    -- Web Banner Illustration: Vector Illustration
    (77, 1),
    (78, 3),
    (79, 4),
    (80, 5);
-- Exchanges
INSERT INTO Exchange (
        freelancer_id,
        client_id,
        service_id,
        status,
        amount
    )
VALUES (2, 5, 1, 'completed', 75.00),
    (3, 6, 2, 'completed', 120.00),
    (4, 7, 3, 'in progress', 250.00),
    (5, 8, 4, 'in progress', 180.00),
    (6, 2, 5, 'completed', 95.00),
    (7, 3, 6, 'cancelled', 85.00);
-- Messages
INSERT INTO Message (sender_id, receiver_id, message)
VALUES (
        2,
        5,
        'Hello! I''ve started working on your character illustration.'
    ),
    (
        5,
        2,
        'Great! Looking forward to seeing the first draft.'
    ),
    (
        3,
        6,
        'Your logo design is ready for review. Please check your email.'
    ),
    (
        6,
        3,
        'Thank you! I just reviewed it and it looks amazing.'
    ),
    (
        4,
        7,
        'I need some additional details for your oil portrait.'
    ),
    (7, 4, 'Sure, what information do you need?');
-- Reviews
INSERT INTO Review (user_id, service_id, rating, comment)
VALUES (
        5,
        1,
        5,
        'Excellent work! The character illustration exceeded my expectations.'
    ),
    (
        6,
        2,
        4,
        'Great logo design. Professional and responsive service.'
    ),
    (
        2,
        5,
        5,
        'Beautiful handcrafted jewelry. Will definitely order again!'
    ),
    (
        3,
        6,
        3,
        'Good design but could have used more detail in some areas.'
    );
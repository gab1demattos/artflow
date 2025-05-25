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
        '701f81be760ef4fada8917640b07b398c855c854',
        'Digital artist specializing in character design and concept art.',
        '/images/user_pfp/man.jpg'
    ),
    (
        'regular',
        'Jane Smith',
        'janesmith',
        'jane@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Brand identity designer with 5+ years of experience working with startups.',
        '/images/user_pfp/woman.jpg'
    ),
    (
        'regular',
        'Michael Johnson',
        'michaelj',
        'michael@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Traditional artist focusing on oil painting and portraits.',
        '/images/user_pfp/man.jpg'
    ),
    (
        'regular',
        'Sarah Williams',
        'sarahw',
        'sarah@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        '3D modeling expert with background in game development.',
        '/images/user_pfp/woman.jpg'
    ),
    (
        'regular',
        'David Brown',
        'davidb',
        'david@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Jewelry craftsman with a passion for unique handmade designs.',
        '/images/user_pfp/man.jpg'
    ),
    (
        'regular',
        'Emily Dickinson',
        'emilyd',
        'emily@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Tattoo artist specializing in custom designs and cover-ups.',
        '/images/user_pfp/woman.jpg'
    ),
    (
        'regular',
        'Alex Thompson',
        'alext',
        'alex@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
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
    ),
    -- Additional users
    (
        'regular',
        'Carlos Rodriguez',
        'carlosr',
        'carlos@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Graphic designer specializing in minimalist design and typography.',
        '/images/user_pfp/man.jpg'
    ),
    (
        'regular',
        'Sophia Chen',
        'sophiac',
        'sophia@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'UI/UX designer creating seamless digital experiences.',
        '/images/user_pfp/woman.jpg'
    ),
    (
        'regular',
        'Ahmed Khan',
        'ahmedk',
        'ahmed@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Animation specialist with expertise in motion graphics.',
        '/images/user_pfp/man.jpg'
    ),
    (
        'regular',
        'Maria Santos',
        'marias',
        'maria@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Ceramics artist with a focus on functional pottery.',
        '/images/user_pfp/woman.jpg'
    ),
    (
        'regular',
        'Jason Lee',
        'jasonl',
        'jason@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Photographer specializing in portrait and street photography.',
        '/images/user_pfp/man.jpg'
    ),
    (
        'regular',
        'Nina Patel',
        'ninap',
        'nina@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Fashion designer focused on sustainable clothing.',
        '/images/user_pfp/woman.jpg'
    ),
    (
        'regular',
        'Lucas Martin',
        'lucasm',
        'lucas@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Comic artist creating original webcomics and illustrations.',
        '/images/user_pfp/man.jpg'
    ),
    (
        'regular',
        'Emma Wilson',
        'emmaw',
        'emma@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Calligrapher and hand-lettering artist with an elegant style.',
        '/images/user_pfp/woman.jpg'
    ),
    (
        'regular',
        'Daniel Kim',
        'danielk',
        'daniel@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Digital sculptor specializing in character and creature design.',
        '/images/user_pfp/man.jpg'
    ),
    (
        'regular',
        'Isabella Garcia',
        'isabellag',
        'isabella@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Watercolor artist creating dreamy landscapes and botanical illustrations.',
        '/images/user_pfp/woman.jpg'
    ),
    (
        'regular',
        'Thomas Anderson',
        'thomasa',
        'thomas@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Sound designer and composer for interactive media.',
        '/images/user_pfp/man.jpg'
    ),
    (
        'regular',
        'Olivia Baker',
        'oliviab',
        'olivia@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Textile artist specializing in woven and printed fabrics.',
        '/images/user_pfp/woman.jpg'
    ),
    (
        'regular',
        'Ryan Foster',
        'ryanf',
        'ryan@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Web designer with expertise in responsive design and WordPress.',
        '/images/user_pfp/man.jpg'
    ),
    (
        'regular',
        'Zoe Mitchell',
        'zoem',
        'zoe@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Illustrator creating whimsical characters for children''s books.',
        '/images/user_pfp/woman.jpg'
    ),
    (
        'regular',
        'Marcus Chen',
        'marcusc',
        'marcus@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        NULL,
        '/images/user_pfp/populators/man1.jpg'
    ),
    (
        'regular',
        'Rachel Torres',
        'rachelt',
        'rachel@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Digital marketing specialist and part-time photographer.',
        '/images/user_pfp/populators/woman1.jpg'
    ),
    (
        'regular',
        'James Wilson',
        'jwilson',
        'jwilson@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        NULL,
        NULL
    ),
    (
        'regular',
        'Sophia Martinez',
        'smartinez',
        'sophia.m@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Visual effects artist specializing in particle systems and dynamics.',
        '/images/user_pfp/populators/woman2.jpg'
    ),
    (
        'regular',
        'Adrian Kumar',
        'adriank',
        'adrian.k@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Motion graphics designer and VFX supervisor.',
        '/images/user_pfp/populators/man2.jpg'
    ),
    (
        'regular',
        'Lena Park',
        'lpark',
        'lena.p@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        NULL,
        '/images/user_pfp/populators/woman3.jpg'
    ),
    (
        'regular',
        'Victor Rossi',
        'vrossi',
        'victor.r@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Street art enthusiast and mural painter.',
        '/images/user_pfp/populators/man3.jpg'
    ),
    (
        'regular',
        'Maya Johnson',
        'mjohnson',
        'maya.j@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Paper craft artist creating intricate 3D designs.',
        NULL
    ),
    (
        'regular',
        'Felix Wong',
        'fwong',
        'felix.w@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        NULL,
        '/images/user_pfp/populators/man4.jpg'
    ),
    (
        'regular',
        'Elena Popov',
        'epopov',
        'elena.p@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Digital sculptor and 3D printing enthusiast.',
        '/images/user_pfp/populators/woman4.jpg'
    ),
    (
        'regular',
        'Kai Nakamura',
        'knakamura',
        'kai.n@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Game environment artist and level designer.',
        '/images/user_pfp/populators/man1.jpg'
    ),
    (
        'regular',
        'Beatrice Silva',
        'bsilva',
        'beatrice.s@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        NULL,
        '/images/user_pfp/populators/woman2.jpg'
    ),
    (
        'regular',
        'Hassan Ahmed',
        'hahmed',
        'hassan.a@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Architectural visualization expert and 3D renderer.',
        NULL
    ),
    (
        'regular',
        'Luna Chang',
        'lchang',
        'luna.c@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Digital art instructor specializing in concept art.',
        '/images/user_pfp/populators/woman3.jpg'
    ),
    (
        'regular',
        'Gabriel Santos',
        'gsantos',
        'gabriel.s@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        NULL,
        '/images/user_pfp/populators/man2.jpg'
    ),
    (
        'regular',
        'Nadia Patel',
        'npatel',
        'nadia.p@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Character rigger for animated films and games.',
        '/images/user_pfp/populators/woman1.jpg'
    ),
    (
        'regular',
        'Oscar Fernandez',
        'ofernandez',
        'oscar.f@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Storyboard artist with experience in animated series.',
        '/images/user_pfp/populators/man3.jpg'
    ),
    (
        'regular',
        'Yuki Tanaka',
        'ytanaka',
        'yuki.t@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        NULL,
        '/images/user_pfp/populators/woman4.jpg'
    ),
    (
        'regular',
        'Leo Costa',
        'lcosta',
        'leo.c@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Traditional sculptor transitioning to digital art.',
        '/images/user_pfp/populators/man4.jpg'
    ),
    (
        'regular',
        'Alice Bennett',
        'abennett',
        'alice.b@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Creative director specialized in branding campaigns.',
        '/images/user_pfp/populators/woman1.jpg'
    ),
    (
        'regular',
        'Marco Rossi',
        'mrossi',
        'marco.r@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Product designer focusing on sustainable materials.',
        NULL
    ),
    (
        'regular',
        'Priya Kumar',
        'pkumar',
        'priya.k@example.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        'Digital illustrator creating fantasy art and book covers.',
        '/images/user_pfp/populators/woman2.jpg'
    ),
    (
        'admin',
        'System Admin',
        'sysadmin',
        'system@artflow.com',
        '701f81be760ef4fada8917640b07b398c855c854',
        NULL,
        NULL
    );
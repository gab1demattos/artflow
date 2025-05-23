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
    );
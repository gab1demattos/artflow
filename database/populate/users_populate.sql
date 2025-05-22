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
    );
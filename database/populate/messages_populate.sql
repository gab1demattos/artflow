-- Messages with properly escaped double quotes
INSERT INTO Message (sender_id, receiver_id, message)
VALUES (
        2,
        5,
        'Hello! Ive started working on your character illustration.'
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
    (7, 4, 'Sure, what information do you need?'),
    -- Additional messages for various services
    (
        2, 
        8, 
        'Hi Alex! I wanted to discuss your requirements for the digital art poster you ordered.'
    ),
    (
        8, 
        2, 
        'Hello John! Id like it to have a cyberpunk theme with neon colors. Can you do that?'
    ),
    (
        2, 
        8, 
        'Absolutely! I specialize in cyberpunk aesthetics. Ill send you a sketch by tomorrow.'
    ),
    (
        8, 
        2, 
        'Sounds perfect! Looking forward to it.'
    ),
    -- Conversation about 3D modeling
    (
        5, 
        4, 
        'Michael, I have some questions about the game assets you ordered.'
    ),
    (
        4, 
        5, 
        'Hi Sarah, go ahead!'
    ),
    (
        5, 
        4, 
        'What game engine will you be using? That will help me optimize the models correctly.'
    ),
    (
        4, 
        5, 
        'Im using Unity 3D. I need low-poly assets to ensure good performance.'
    ),
    (
        5, 
        4, 
        'Perfect, Ill make sure theyre optimized for Unity. Ill get started right away.'
    ),
    -- Conversation about handcrafted jewelry
    (
        6, 
        3, 
        'Jane, I have some designs ready for your handcrafted jewelry order.'
    ),
    (
        3, 
        6, 
        'Cant wait to see them, David! What materials are you planning to use?'
    ),
    (
        6, 
        3, 
        'Im thinking of using sterling silver with some turquoise accents. Would that work for you?'
    ),
    (
        3, 
        6, 
        'That sounds beautiful! Yes, please proceed with that combination.'
    ),
    -- Conversation about tattoo design
    (
        7, 
        2, 
        'About your tattoo design request - do you have any reference images you can share?'
    ),
    (
        2, 
        7, 
        'Yes Emily, Ive been collecting some inspiration. Ill email them to you right now.'
    ),
    (
        7, 
        2, 
        'Got them, thanks! I think I understand the style youre going for. Ill create something unique.'
    ),
    (
        2, 
        7, 
        'Great! Im excited to see what you come up with. Its my first tattoo so Im a bit nervous.'
    ),
    (
        7, 
        2, 
        'Dont worry, Ill make sure its perfect. We can make adjustments until youre completely satisfied.'
    );
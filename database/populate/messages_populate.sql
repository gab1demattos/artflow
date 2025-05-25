-- Messages with properly escaped single quotes
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
        'Hello John! I''d like it to have a cyberpunk theme with neon colors. Can you do that?'
    ),
    (
        2, 
        8, 
        'Absolutely! I specialize in cyberpunk aesthetics. I''ll send you a sketch by tomorrow.'
    ),
    (
        8, 
        2, 
        'Sounds perfect! Looking forward to it.'
    ),
    -- More John Doe conversations (additional conversations for John Doe - user ID 2)
    (
        2,
        9,
        'Hi Carlos, I received your request for the minimalist logo design. Can you tell me more about your brand?'
    ),
    (
        9,
        2,
        'Hi John! My brand focuses on sustainable tech solutions. I want something clean and modern that conveys innovation.'
    ),
    (
        2,
        9,
        'Got it. I''m thinking a simple geometric shape with a clean font. How does that sound?'
    ),
    (
        9,
        2,
        'That sounds exactly what I''m looking for. I''m excited to see your concepts!'
    ),
    -- John and Sophia (UI/UX designer)
    (
        2,
        10,
        'Sophia, I was wondering if you''d be interested in collaborating on a mobile app interface? I need UI expertise.'
    ),
    (
        10,
        2,
        'I''d love to collaborate, John! What kind of app are you working on?'
    ),
    (
        2,
        10,
        'It''s an art portfolio app for digital artists. I can handle the illustrations but need help with the user flow.'
    ),
    (
        10,
        2,
        'Sounds like an interesting project! Send me the details and we can schedule a meeting to discuss the specifics.'
    ),
    -- John and Ahmed (animator)
    (
        2,
        11,
        'Ahmed, I saw your animation work and I''m impressed! Would you be able to animate some of my character designs?'
    ),
    (
        11,
        2,
        'Thanks John! I''d be happy to collaborate. What kind of animations are you looking for?'
    ),
    (
        2,
        11,
        'Just some simple movement cycles - walking, running, and maybe a few action poses.'
    ),
    (
        11,
        2,
        'That should be doable. Please send over your character sheets and we can discuss the timeline and pricing.'
    ),
    -- John and Maria (ceramics artist)
    (
        2,
        12,
        'Maria, I''m looking for someone to create custom ceramic mugs with my artwork printed on them. Is that something you could do?'
    ),
    (
        12,
        2,
        'Hi John! I definitely can help with that. I have a process for transferring artwork onto my ceramic pieces.'
    ),
    (
        2,
        12,
        'Perfect! I''d like to start with a small batch of 5 mugs featuring different characters.'
    ),
    (
        12,
        2,
        'Sounds good. If you can send me the digital files, I can give you a quote and timeline for the project.'
    ),
    -- John and Jason (photographer)
    (
        2,
        13,
        'Jason, I need professional photos of my artwork for my portfolio. Are you available for a shoot?'
    ),
    (
        13,
        2,
        'Hi John, I''d be happy to photograph your work. What kind of pieces do you have?'
    ),
    (
        2,
        13,
        'Mainly digital prints and a few canvas paintings. The largest is 36x48 inches.'
    ),
    (
        13,
        2,
        'No problem. I have studio space that would work well. When would you like to schedule the shoot?'
    ),
    -- John and Nina (fashion designer)
    (
        2,
        14,
        'Nina, I''m working on character designs for a fashion-focused game. Would you be interested in consulting?'
    ),
    (
        14,
        2,
        'That sounds intriguing, John! I love the intersection of fashion and gaming.'
    ),
    (
        2,
        14,
        'Great! I need help making sure the clothing designs are realistic yet stylized. Your portfolio shows exactly the aesthetic I''m going for.'
    ),
    (
        14,
        2,
        'I''m excited to help! Let me know when you''d like to discuss the details further.'
    ),
    -- John and Lucas (comic artist)
    (
        2,
        15,
        'Lucas, I''m a fan of your comic work! I''m putting together an anthology and wondered if you''d like to contribute?'
    ),
    (
        15,
        2,
        'Thanks for reaching out, John! I''d potentially be interested. What theme are you going for with the anthology?'
    ),
    (
        2,
        15,
        'We''re focusing on short sci-fi stories, 4-6 pages each. The deadline would be in three months.'
    ),
    (
        15,
        2,
        'Sci-fi is my favorite genre to work in. Count me in! Send me the details and guidelines when you have them.'
    ),
    -- John and Emma (calligrapher)
    (
        2,
        16,
        'Emma, I need elegant lettering for a book cover I''m designing. Would you be available for commission?'
    ),
    (
        16,
        2,
        'Hi John! I''d love to work on a book cover. What style of calligraphy are you looking for?'
    ),
    (
        2,
        16,
        'I''m thinking something flowing and romantic, but still legible. It''s for a fantasy novel.'
    ),
    (
        16,
        2,
        'That sounds right up my alley. I can create some samples in different styles for you to choose from.'
    ),
    -- John and Daniel (digital sculptor)
    (
        2,
        17,
        'Daniel, I have a character design that I''d like to see turned into a 3D model. Is that something you could help with?'
    ),
    (
        17,
        2,
        'Absolutely, John! I specialize in turning 2D concepts into 3D sculptures.'
    ),
    (
        2,
        17,
        'Perfect! It''s a creature design for a personal project. I''ll send over the turnarounds and reference sheets.'
    ),
    (
        17,
        2,
        'Looking forward to seeing it. Once I review the materials, I can give you a timeline and cost estimate.'
    ),
    -- John and Isabella (watercolor artist)
    (
        2,
        18,
        'Isabella, I''m curious if you''d be interested in a collaboration? I do digital work but love the texture of watercolor.'
    ),
    (
        18,
        2,
        'A collaboration sounds wonderful, John! What did you have in mind?'
    ),
    (
        2,
        18,
        'I was thinking I could create the line art digitally, and you could add watercolor textures and backgrounds?'
    ),
    (
        18,
        2,
        'That would be a beautiful combination of our styles. Let''s do it! We could start with a small test piece.'
    ),
    -- John and Thomas (sound designer)
    (
        2,
        19,
        'Thomas, I''m creating an animated short and need some sound design. Would you be interested?'
    ),
    (
        19,
        2,
        'Hi John, I''d definitely be interested! How long is the piece and what''s the theme?'
    ),
    (
        2,
        19,
        'It''s about 3 minutes, a surreal journey through different dreamscapes. I need ambient music and sound effects.'
    ),
    (
        19,
        2,
        'Sounds like a fascinating project. I''d love to work on it. When would you need the audio completed?'
    ),
    -- John and Olivia (textile artist)
    (
        2,
        20,
        'Olivia, I''m designing merchandise with my artwork and need advice on fabric printing. Could you help?'
    ),
    (
        20,
        2,
        'I''d be happy to help, John! There are several methods depending on what you''re looking for.'
    ),
    (
        2,
        20,
        'I''m mainly interested in creating scarves and perhaps some wall hangings with my abstract designs.'
    ),
    (
        20,
        2,
        'For scarves, silk screening or digital printing would work well. Wall hangings could be done with digital printing on canvas or even woven. Let me know what appeals to you most!'
    ),
    -- Additional conversations between other users
    (
        3, 
        10, 
        'Sophia, I need a UI revamp for my company''s website. Are you taking new clients?'
    ),
    (
        10, 
        3, 
        'Hi Jane! Yes, I''m currently accepting new projects. I''d love to hear more about what you''re looking for.'
    ),
    (
        4, 
        18, 
        'Isabella, your watercolor landscapes are incredible. Would you be interested in doing a commissioned piece?'
    ),
    (
        18, 
        4, 
        'Thank you, Michael! I''d be happy to work on a commission for you. What did you have in mind?'
    ),
    (
        5, 
        17, 
        'Daniel, I need some 3D character models for an indie game I''m developing. Would you be interested?'
    ),
    (
        17, 
        5, 
        'Hi Sarah! Game assets are my specialty. Tell me more about the style and number of characters you need.'
    ),
    (
        6, 
        14, 
        'Nina, I''m looking for custom jewelry that complements your clothing designs. Is that something we could collaborate on?'
    ),
    (
        14, 
        6, 
        'David, that sounds like a wonderful collaboration! I think our styles would complement each other perfectly.'
    ),
    (
        7, 
        15, 
        'Lucas, would you be interested in collaborating on a graphic novel? I have a story but need an illustrator.'
    ),
    (
        15, 
        7, 
        'Hi Emily! I''d love to hear more about your story. What genre are you working in?'
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
        'I''m using Unity 3D. I need low-poly assets to ensure good performance.'
    ),
    (
        5, 
        4, 
        'Perfect, I''ll make sure they''re optimized for Unity. I''ll get started right away.'
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
        'Can''t wait to see them, David! What materials are you planning to use?'
    ),
    (
        6, 
        3, 
        'I''m thinking of using sterling silver with some turquoise accents. Would that work for you?'
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
        'Yes Emily, I''ve been collecting some inspiration. I''ll email them to you right now.'
    ),
    (
        7, 
        2, 
        'Got them, thanks! I think I understand the style you''re going for. I''ll create something unique.'
    ),
    (
        2, 
        7, 
        'Great! I''m excited to see what you come up with. It''s my first tattoo so I''m a bit nervous.'
    ),
    (
        7, 
        2, 
        'Don''t worry, I''ll make sure it''s perfect. We can make adjustments until you''re completely satisfied.'
    ),
    -- Additional conversations about art collaborations and commissions
    (
        10, 
        13, 
        'Jason, I need professional photos of my UI/UX portfolio for a competition. Are you available?'
    ),
    (
        13, 
        10, 
        'I can definitely help with that, Sophia. When do you need the photos by?'
    ),
    (
        11, 
        15, 
        'Lucas, I see you''re a comic artist. Would you be interested in collaborating on an animated comic series?'
    ),
    (
        15, 
        11, 
        'That sounds amazing, Ahmed! I''ve always wanted to see my characters animated.'
    ),
    (
        12, 
        20, 
        'Olivia, would you be interested in a collaboration combining ceramics and textiles?'
    ),
    (
        20, 
        12, 
        'What an intriguing idea, Maria! I could definitely see some beautiful possibilities there.'
    ),
    (
        9, 
        16, 
        'Emma, I need elegant calligraphy for a corporate branding project. Are you available?'
    ),
    (
        16, 
        9, 
        'I''d be happy to work on that, Carlos. Could you tell me more about the brand and the style you''re going for?'
    ),
    -- More diverse conversations
    (
        2, 
        21, 
        'Ryan, I need a website to showcase my digital art portfolio. Can you help with that?'
    ),
    (
        21, 
        2, 
        'Hi John! I''d be happy to build a site that showcases your work beautifully. Do you have any specific features in mind?'
    ),
    (
        2,
        21,
        'I want a minimalist design with a gallery view and the ability to sell prints directly.'
    ),
    (
        21,
        2,
        'That sounds doable. I can build you a custom WordPress site with WooCommerce for sales. When would you like to get started?'
    ),
    -- John and Zoe
    (
        2,
        22,
        'Zoe, I saw your children''s book illustrations and loved them! Would you be interested in collaborating on a project?'
    ),
    (
        22,
        2,
        'Thank you, John! I''d definitely be interested in a collaboration. What kind of project did you have in mind?'
    )
    ,
    (
        2,
        22,
        'I''m writing a short children''s story about a little robot learning to paint. I think your whimsical style would be perfect.'
    ),
    (
        22,
        2,
        'That sounds adorable! I love drawing robots. Send me your story when you''re ready, and we can discuss details.'
    ),
    -- Additional conversations between various users
    (
        8,
        11,
        'Ahmed, I need some animation work for a game trailer. Are you available for freelance work?'
    ),
    (
        11,
        8,
        'Hi Alex! Yes, I''m currently taking freelance animation projects. What style are you looking for?'
    ),
    (
        19,
        13,
        'Jason, I need photographs of my audio equipment for my portfolio. Can you help?'
    ),
    (
        13,
        19,
        'Sure thing, Thomas. I have experience photographing audio equipment and instruments.'
    ),
    (
        14,
        18,
        'Isabella, would you be interested in creating watercolor patterns that I could use for a clothing line?'
    ),
    (
        18,
        14,
        'That sounds like an exciting project, Nina! I love the idea of seeing my art on clothing.'
    ),
    -- More John Doe conversations
    (
        2,
        3,
        'Jane, I was thinking of refreshing my brand. Would you have time for a consultation?'
    ),
    (
        3,
        2,
        'Of course, John! I''d be happy to help you refresh your visual identity. When works for you?'
    ),
    (
        2,
        3,
        'Maybe next week? I''m still gathering inspiration but want to start the process.'
    ),
    (
        3,
        2,
        'Next week works perfectly. Send over any inspiration you''ve collected, and we''ll set up a proper consultation.'
    ),
    -- John and Michael
    (
        2,
        4,
        'Michael, I admire your oil painting technique. Would you be willing to do a virtual workshop for my art group?'
    ),
    (
        4,
        2,
        'I''d be honored, John! I enjoy teaching and sharing techniques. How many people would be participating?'
    ),
    (
        2,
        4,
        'There would be about 8-10 artists, all with different experience levels. We could do it over Zoom.'
    ),
    (
        4,
        2,
        'That sounds perfect. I can prepare a demonstration and then work with each participant. Let me know what date works for your group.'
    ),
    -- Marcus and Rachel discussing photography
    (
        23,
        24,
        'Hi Rachel, I saw you do photography. Could you help me with product shots of my digital art prints?'
    ),
    (
        24,
        23,
        'Of course, Marcus! I specialize in art photography. Do you have the prints ready?'
    ),
    (
        23,
        24,
        'I''ll have them ready by next week. They''re metallic prints, so we''ll need good lighting to capture the effect.'
    ),
    (
        24,
        23,
        'Perfect! Metallic prints are tricky but I have experience with those. My studio has specialized lighting for reflective surfaces.'
    ),
    -- Sophia and Adrian discussing VFX collaboration
    (
        26,
        27,
        'Adrian, I''m working on a project that needs both particle effects and motion graphics. Would you be interested in collaborating?'
    ),
    (
        27,
        26,
        'Definitely! That''s right up my alley. What kind of project is it?'
    ),
    (
        26,
        27,
        'It''s a title sequence for an indie film. I''m handling the particle systems but could use your expertise on the motion graphics.'
    ),
    (
        27,
        26,
        'Sounds exciting! Send me the concept art and we can discuss the visual style you''re going for.'
    ),
    -- Luna and Victor discussing art education
    (
        31,
        29,
        'Victor, I''m putting together an online course on concept art. Would you be interested in doing a guest lecture on street art influences?'
    ),
    (
        29,
        31,
        'That would be amazing! I love sharing how street art can inspire digital work.'
    ),
    (
        31,
        29,
        'Great! I''m thinking a 45-minute session about your transition from street art to digital and your creative process.'
    ),
    (
        29,
        31,
        'Perfect timing. I just finished a piece that combines both styles. I can use it as a case study.'
    ),
    -- Elena and Kai discussing game assets
    (
        36,
        37,
        'Hey Kai, I heard you''re looking for 3D models for your game environment. I''d love to help!'
    ),
    (
        37,
        36,
        'Elena! Yes, I need some environmental props and possibly some creature models.'
    ),
    (
        36,
        37,
        'I can handle both. My 3D printing background helps me create very printable game assets too, if you ever want to make merchandise.'
    ),
    (
        37,
        36,
        'That''s perfect! Let''s start with the environment props. I''ll send you the art direction document.'
    ),
    -- Hassan and Beatrice discussing architectural visualization
    (
        39,
        38,
        'Beatrice, I''m working on an architectural project and could use some help with the interior visualization.'
    ),
    (
        38,
        39,
        'I''d be happy to help, Hassan. What kind of space are you working on?'
    ),
    (
        39,
        38,
        'It''s a modern art gallery. I need help with the lighting and material design.'
    ),
    (
        38,
        39,
        'That''s exactly the kind of project I enjoy. Send me the floor plans and we can discuss the aesthetic direction.'
    ),
    -- Nadia and Oscar discussing animation
    (
        42,
        43,
        'Oscar, I have some character rigs that need storyboarding for an animated short. Would you be interested?'
    ),
    (
        43,
        42,
        'I''d love to help, Nadia! Your character rigs are always fun to work with.'
    ),
    (
        42,
        43,
        'Great! It''s a 2-minute sequence with lots of action. I''ll send you the character sheets and story outline.'
    ),
    (
        43,
        42,
        'Perfect timing! I just finished my current project. When do you need the storyboards?'
    ),
    -- Leo and Alice discussing mixed media
    (
        45,
        46,
        'Alice, I''m transitioning from traditional to digital sculpture and would love your insight on branding this new direction.'
    ),
    (
        46,
        45,
        'I''d be happy to help, Leo! It''s all about highlighting how your traditional skills enhance your digital work.'
    ),
    (
        45,
        46,
        'That''s exactly what I want to convey. Could we schedule a branding consultation?'
    ),
    (
        46,
        45,
        'Of course! I have some ideas already. Let''s meet next week to discuss the direction.'
    ),
    -- Marco and Priya discussing product design
    (
        47,
        48,
        'Priya, your fantasy art style would work beautifully for a product line I''m developing. Would you be interested in licensing some artwork?'
    ),
    (
        48,
        47,
        'That sounds intriguing, Marco! What kind of products are you designing?'
    ),
    (
        47,
        48,
        'Eco-friendly home decor items - think wall art, throws, and decorative objects with a fantasy twist.'
    ),
    (
        48,
        47,
        'I love the combination of sustainability and fantasy! Let''s discuss which pieces would work best for your products.'
    ),
    -- Cross-disciplinary collaborations
    (
        31,
        42,
        'Nadia, would you be interested in creating a workshop on rigging for my digital art students?'
    ),
    (
        42,
        31,
        'That would be great, Luna! We could cover both basic and advanced rigging techniques.'
    ),
    (
        36,
        39,
        'Hassan, I''d love to learn more about architectural visualization. Could we do a skill exchange?'
    ),
    (
        39,
        36,
        'Absolutely! I could definitely use some pointers on digital sculpting in return. When are you free to start?'
    ),
    -- Mobile game project discussion
    (
        37,
        23,
        'Marcus, I need some character concepts for a mobile game. Your style would be perfect!'
    ),
    (
        23,
        37,
        'Thanks, Kai! What''s the game about? I''d love to help create the characters.'
    ),
    (
        37,
        23,
        'It''s a puzzle adventure game with a sci-fi theme. Need 5 main characters and some NPCs.'
    ),
    (
        23,
        37,
        'Sounds fun! I have experience with mobile game art. Let''s make sure they look good even at small sizes.'
    );
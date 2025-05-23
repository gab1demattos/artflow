-- Reviews for completed exchanges
-- Only clients who have completed exchanges can leave reviews

-- Reviews for Exchange ID 1: Client 5 ordered service 1 from freelancer 2
INSERT INTO Review (
        user_id,
        service_id,
        rating,
        comment,
        created_at
    )
VALUES (
        5,
        1,
        5,
        'Excellent logo design! Perfectly captured our tech startup vibe with the blue color scheme. Very professional and responsive freelancer.',
        '2025-05-15 09:23:14'
    );

-- Reviews for Exchange ID 2: Client 6 ordered service 2 from freelancer 3
INSERT INTO Review (
        user_id,
        service_id,
        rating,
        comment,
        created_at
    )
VALUES (
        6,
        2,
        4,
        'Great brand identity work for our bakery. The pink elements were incorporated beautifully. Only reason for 4 stars is a slight delay in delivery.',
        '2025-05-14 14:45:32'
    );

-- Reviews for Exchange ID 6: Client 3 ordered service 6 from freelancer 7
INSERT INTO Review (
        user_id,
        service_id,
        rating,
        comment,
        created_at
    )
VALUES (
        3,
        6,
        5,
        'The vintage-style logo for our bakery is perfect! Exceeded all expectations and delivered ahead of schedule.',
        '2025-05-22 17:12:05'
    );

-- Reviews for Exchange ID 8: Client 6 ordered service 8 from freelancer 2
INSERT INTO Review (
        user_id,
        service_id,
        rating,
        comment,
        created_at
    )
VALUES (
        6,
        8,
        5,
        'Amazing geometric tattoo design. Clean lines and exactly what I was looking for. Will definitely order again.',
        '2025-05-23 10:06:41'
    );

-- Reviews for Exchange ID 10: Client 5 ordered service 10 from freelancer 4
INSERT INTO Review (
        user_id,
        service_id,
        rating,
        comment,
        created_at
    )
VALUES (
        5,
        10,
        3,
        'The 3D model of the lamp was good, but required several revisions to match our modern style vision. Decent work overall.',
        '2025-05-22 15:38:53'
    );

-- Reviews for Exchange ID 12: Client 8 ordered service 12 from freelancer 6
INSERT INTO Review (
        user_id,
        service_id,
        rating,
        comment,
        created_at
    )
VALUES (
        8,
        12,
        5,
        'Perfect minimal logo for our tech event. Clean, modern, and exactly what we needed. Fast communication and delivery!',
        '2025-05-23 09:17:29'
    );

-- Additional reviews for balance
INSERT INTO Review (
        user_id,
        service_id,
        rating,
        comment,
        created_at
    )
VALUES (
        2,
        5,
        4,
        'Good quality 3D model of the chair. Modern style was well-executed. Could have used more detailed texturing.',
        '2025-05-21 13:44:10'
    );

-- Reviews for new completed exchanges

-- Reviews for Exchange ID 13: Client 4 ordered service 1 from freelancer 2
INSERT INTO Review (
        user_id,
        service_id,
        rating,
        comment,
        created_at
    )
VALUES (
        4,
        1,
        4,
        'Great logo redesign for our fashion brand. Communication was smooth, and the designer understood our vision.',
        '2025-05-07 11:21:35'
    );

-- Reviews for Exchange ID 14: Client 7 ordered service 2 from freelancer 3
INSERT INTO Review (
        user_id,
        service_id,
        rating,
        comment,
        created_at
    )
VALUES (
        7,
        2,
        5,
        'Outstanding brand identity for our restaurant! Every element was thoughtfully designed. Will definitely recommend.',
        '2025-05-10 16:32:47'
    );

-- Reviews for Exchange ID 15: Client 5 ordered service 7 from freelancer 8
INSERT INTO Review (
        user_id,
        service_id,
        rating,
        comment,
        created_at
    )
VALUES (
        5,
        7,
        5,
        'The digital illustration for our book cover is stunning. Captured the essence of the story perfectly.',
        '2025-05-17 12:09:54'
    );

-- Reviews for Exchange ID 16: Client 8 ordered service 3 from freelancer 4
INSERT INTO Review (
        user_id,
        service_id,
        rating,
        comment,
        created_at
    )
VALUES (
        8,
        3,
        5,
        'Beautiful oil painting of our family. The attention to detail is remarkable, and the artist was a pleasure to work with.',
        '2025-05-04 14:19:23'
    );

-- Reviews for Exchange ID 17: Client 2 ordered service 6 from freelancer 7
INSERT INTO Review (
        user_id,
        service_id,
        rating,
        comment,
        created_at
    )
VALUES (
        2,
        6,
        4,
        'Very nice logo design for our fitness studio. Clean, modern, and energetic, just as requested.',
        '2025-05-19 17:42:08'
    );

-- Reviews for Exchange ID 18: Client 3 ordered service 4 from freelancer 5
INSERT INTO Review (
        user_id,
        service_id,
        rating,
        comment,
        created_at
    )
VALUES (
        3,
        4,
        5,
        'Incredibly detailed tattoo sleeve design. The artist incorporated all my ideas while adding their artistic touch. Couldn´t be happier!',
        '2025-05-20 11:28:36'
    );
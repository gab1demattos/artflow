-- filepath: /home/francisca/uni/2ano/2S/LTW/ltw-project-ltw07g05/database/populate/long_conversation.sql
-- A really long conversation between John Doe and Emma Wilson about a book cover design
INSERT INTO Message (sender_id, receiver_id, message)
VALUES 
    -- Initial project inquiry
    (
        2, -- John Doe
        16, -- Emma Wilson
        'Hi Emma! I''ve been following your calligraphy work and I''m really impressed with your style. I''m working on a fantasy book cover design for a client and would love your input on the lettering.'
    ),
    (
        16,
        2,
        'Thanks so much for reaching out, John! I''d be thrilled to collaborate on a book cover. Can you tell me more about the project and what kind of lettering style you''re envisioning?'
    ),
    (
        2,
        16,
        'It''s a high fantasy novel called "The Crystal Kingdoms." The author wants something elegant but with a mystical feel. The cover illustration will feature a crystalline castle with mountains in the background.'
    ),
    (
        16,
        2,
        'That sounds beautiful! For high fantasy with a mystical element, I could see something with flourishes and a slight ethereal quality. Would you prefer something more traditional or with a modern twist?'
    ),
    (
        2,
        16,
        'I think more traditional would fit better with the overall aesthetic. The author referenced books like Lord of the Rings and Wheel of Time for the general feel they''re going for.'
    ),
    (
        16,
        2,
        'Perfect, that gives me a clear direction. Would you like me to hand-letter just the title, or are you looking for author name and any tagline as well?'
    ),
    (
        2, 
        16,
        'For now, let''s focus on the title. If the author likes your style, we might add the author name in a matching style later. The title needs to be the main focus though - "The Crystal Kingdoms" should really pop against the background.'
    ),
    (
        16,
        2,
        'Understood. Do you have any color preferences? I''m thinking a silver or white lettering with perhaps subtle blue highlights would complement the crystal theme.'
    ),
    (
        2,
        16,
        'That''s exactly what I was thinking! Silver with blue highlights would be perfect. The overall color scheme of the illustration will be cool blues and purples with silver accents.'
    ),
    (
        16,
        2,
        'Sounds like we''re on the same page! What''s your timeline for this project? I''d like to sketch a few concepts first before moving to the final design.'
    ),
    (
        2,
        16,
        'The full cover is due in about 6 weeks, but I''d like to have the lettering finalized in the next 2-3 weeks so I can integrate it properly with the illustration. Would that work for you?'
    ),
    (
        16,
        2,
        'That timeline works perfectly for me. I can have 3-4 concept sketches to you by the end of next week, and then we can refine from there. Do you have the exact dimensions for the title placement?'
    ),
    (
        2,
        16,
        'Great! The cover is 6x9 inches, and the title will be positioned in the upper third of the cover. I''ll send you a template with the safe zones marked so you know exactly where to place it.'
    ),
    (
        16,
        2,
        'Perfect, that template would be very helpful. One more question - does the author have any specific fonts they already like? Sometimes clients have seen examples they want to emulate.'
    ),
    (
        2,
        16,
        'They mentioned liking the typography from the newer editions of The Name of the Wind and The Way of Kings. Nothing too ornate, but definitely fancy enough to feel magical and important.'
    ),
    (
        16,
        2,
        'Those are great references! Both have a clean but fantasy-appropriate feel. I''ll keep those in mind while developing the concepts. I''ll aim for something that feels magical without being difficult to read.'
    ),
    (
        2,
        16,
        'That sounds perfect. I''ve just emailed you the template and a mood board with some additional visual references. Let me know if you need anything else to get started!'
    ),
    (
        16,
        2,
        'Got them! The mood board is very helpful - I especially like the crystal formations you included. I''ll start sketching and will have something to show you by next Friday. Is there a good time to review them together?'
    ),
    (
        2,
        16,
        'Friday afternoon would work well for me, maybe around 3pm? We could do a quick video call if that works for you, so I can give immediate feedback.'
    ),
    (
        16,
        2,
        'Friday at 3pm works perfectly! A video call would be ideal, as I can walk you through my thinking for each concept. I''m already excited about this project - the crystal theme gives me a lot of interesting elements to work with.'
    ),
    -- A few days later
    (
        16,
        2,
        'John, I''ve been working on the initial sketches and had a question - would you like me to incorporate any crystal-like elements into the letterforms themselves? I was thinking about adding some faceted details to a few key letters.'
    ),
    (
        2,
        16,
        'I love that idea, Emma! Incorporating crystal elements into the letters themselves would tie everything together nicely. Maybe for the T and K in "The" and "Kingdoms"? Those could be the anchor points.'
    ),
    (
        16,
        2,
        'Great suggestion! I''ll focus on those letters as feature points. Also, I was thinking about adding a subtle glow effect around some of the letters - almost like they''re catching light like a real crystal would. Would that be too much?'
    ),
    (
        2,
        16,
        'Not at all - I think that glow effect could be really beautiful if done subtly. It would help enhance the magical quality we''re going for. Just make sure it would work well when printed, not just on screen.'
    ),
    (
        16,
        2,
        'Absolutely, I''ll make sure it''s print-friendly. I''ll prepare versions both with and without the glow so you can see the difference and decide which direction you prefer.'
    ),
    -- Concept review
    (
        16,
        2,
        'Hi John! I''m finished with the initial concepts a bit early and have attached them to an email. There are four different approaches - two more traditional and two with more fantasy elements. Let me know what you think!'
    ),
    (
        2,
        16,
        'Emma, these are absolutely stunning! I''m blown away by concept #3 especially - the crystalline serifs and the way you integrated those faceted elements is exactly what I was imagining but couldn''t articulate. The client is going to love this!'
    ),
    (
        16,
        2,
        'I''m so glad you like them! Concept #3 was my favorite as well. The faceted serifs catch light in an interesting way, and I think they''ll look amazing with the silver and blue treatment we discussed.'
    ),
    (
        2,
        16,
        'Definitely. Do you think we could take the main structure from #3 but add in that subtle glow effect you had in concept #2? That might give us the best of both worlds.'
    ),
    (
        16,
        2,
        'Absolutely! I''ll create a hybrid version combining those elements. Should I keep the elongated descender on the "K" or go with the more balanced approach from concept #3?'
    ),
    (
        2,
        16,
        'Let''s keep the elongated descender - it adds drama and gives me a nice design element to work with in the overall composition. It could even interact with part of the illustration.'
    ),
    (
        16,
        2,
        'Perfect! I''ll work on the refined version and have it to you before our call on Friday. I''m also going to include a version showing how it might look against a darker background, since you mentioned the cover will have those deep blues.'
    ),
    (
        2,
        16,
        'That would be really helpful, thanks! Actually, would it be possible to see it on a simplified version of the background? I can send you a very rough mockup of the cover layout if that helps.'
    ),
    (
        16,
        2,
        'That would be ideal! If you can send a simplified background mockup, I can make sure the lettering works perfectly in context. Even a rough sketch of the layout would help me finalize the composition.'
    ),
    (
        2,
        16,
        'Just sent it over! It''s very rough but should give you the general idea of the castle placement and color scheme. Looking forward to our call on Friday!'
    ),
    (
        16,
        2,
        'Got it! This is perfect - I can now see exactly where the title needs to sit. I''ll have everything ready for our Friday call. I think this is going to be a beautiful cover!'
    ),
    -- After the call
    (
        2,
        16,
        'Emma, thank you for the great call today. The client absolutely loved the final concept! The way you incorporated the feedback so quickly during our call was impressive.'
    ),
    (
        16,
        2,
        'I''m thrilled that the client loved it! It was a fun challenge to make those adjustments in real-time. I''ll incorporate all the final tweaks we discussed and send you the finished vector files by Monday. Would that work?'
    ),
    (
        2,
        16,
        'Monday is perfect. Once I have the final files, I can integrate them with the illustration and send you a preview of how it all looks together before we deliver to the client.'
    ),
    (
        16,
        2,
        'I''d love to see the final cover! It''s always rewarding to see how my lettering works with the complete design. I have a feeling this one is going to be portfolio-worthy for both of us.'
    ),
    (
        2,
        16,
        'Absolutely - I''ll make sure you get high-res copies for your portfolio. This has been such a smooth collaboration. I''d definitely like to work with you on future projects.'
    ),
    (
        16,
        2,
        'Likewise, John! Your clear direction and feedback made this process very enjoyable. I''d be delighted to collaborate on future projects. Don''t hesitate to reach out whenever you need lettering work!'
    );
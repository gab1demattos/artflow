
-- Main populate file that includes all separate population files
-- This is a more maintainable approach than having one large file

-- First populate users
.read populate/users_populate.sql

-- Then populate categories and subcategories
.read populate/categories_populate.sql

-- Then populate services
.read populate/services_populate.sql

-- Then associate services with subcategories
.read populate/service_subcategories.sql

-- Then populate exchanges
.read populate/exchanges_populate.sql

-- Regular message conversations
.read populate/messages_populate.sql

-- Long conversation between John Doe and Emma Wilson
.read populate/long_conversation.sql

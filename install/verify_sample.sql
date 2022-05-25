# This should show entity 1 only, the only entity that can log in.
select entities.FullName,entities.Description,entities.Classification,entity_to_user.Username,credentials.Credential,credentials.CredType 
	FROM entities INNER JOIN entity_to_user INNER JOIN credentials 
	ON entities.ID = entity_to_user.EntityID AND entity_to_user.ID = credentials.UserID;
	
# Entity 1 only has one item checked out....
SELECT assignments.* 
	FROM assignments INNER JOIN entities 
	ON assignments.AssignedTo = entities.ID 
	WHERE entities.ID = 1;
	
# ... as does entity 3 ...
SELECT assignments.* 
	FROM assignments INNER JOIN entities 
	ON assignments.AssignedTo = entities.ID 
	WHERE entities.ID = 3;
	
# ... but Entity 1 checked them both out.
SELECT assignments.* 
	FROM assignments INNER JOIN entities 
	ON assignments.AssignedBy = entities.ID 
	WHERE entities.ID = 1;
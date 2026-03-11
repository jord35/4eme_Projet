 Pour ce projet je vais avoir besoin de plusieurs tables.
 Forcément une table book
  Une table user
 une table message
  Une table conversation.

pour book: id/titre /auteure / Description/propriétaire du livre(id) / date d'ajout / url img/ disponibilité

user : id / mail / pseudo /Date d'enregistrement du compte/ mot de passe haché/ url img/ 

Message:  Date de création du message/  ID de l'envoyeur /  ID de conversation /  Contenu du Message/   Message reçu lu(oui ou non)

conversation: id/user1/user2/last_message_id
___________________________________
 pour récupérer  toutes les conversations d'un utilisateur je map sur conversation avec son id je récupère les id des conversations  L'useur 2  le last message  Je fais un join  pour récupérer les infos   dans la table messages. 

  Puis si l'utilisateur clique sur la conversion
  
  Je récupère tout à partir de l'ID de conversation. Je trie par date. Je trie pour chaque qui a envoyé. Est-ce que l'ID de l'envoyeur est l'ID de l'utilisateur ? Si oui, alors le message a été envoyé par l'utilisateur, sinon le message a été reçu par l'utilisateur.
____________________________________


import nodemailer from 'nodemailer';

const transporter = nodemailer.createTransport({
  host: 'mail.delaware.o2switch.net', // Remplacez par votre domaine ou le nom du serveur SMTP fourni
  port: 465, // Utilisez 465 pour SSL/TLS ou 587 pour STARTTLS
  secure: true, // true pour 465, false pour 587
  auth: {
    user: 'user', // Votre adresse email complète
    pass: 'password', // Le mot de passe de cette adresse email
  },
});

const mailOptions = {
  from: '"c\'est matcha" <match@domaine.fr>',
  to: 'exemple@gmail.com',
  subject: 'Subject',
  text: 'text',
  html: '<p>https://media1.tenor.com/m/Erz50ZMbtvEAAAAd/turbo-granny-dandadan-turbo-granny.gif</p>',
};

transporter.sendMail(mailOptions, (error, info) => {
  if (error) {
    return console.log(error);
  }
  console.log('Email envoyé : %s', info.messageId);
});
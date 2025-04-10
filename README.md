# geekbook-wsl2

[![Pipeline-CI](https://github.com/duncanidaho14/geekbook-wsl2/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/duncanidaho14/geekbook-wsl2/actions/workflows/ci.yml)


##  🛥 Install docker

###  📲 If you are on windows you can install Wsl2
**open Microsoft Store and download Wsl2**
**install ubuntu or debian**

## Découvrez GeekBook : votre nouvelle librairie en ligne !
**GeekBook est la plateforme idéale pour tous les amoureux de livres. Avec une interface moderne et rapide, notre single page application vous permet de parcourir un large catalogue de livres, trouver vos lectures préférées en un instant grâce à notre moteur de recherche ultra-performant MeiliSearch, et finaliser vos achats en toute sécurité avec Stripe.**

**Côté technique, GeekBook repose sur un écosystème robuste :**
    - Développée avec Symfony 6 pour des performances optimales.
    - Application Dockeriser pour une flexibilité totale.
    - Sécurisée par Traefik pour la gestion HTTPS.



## 💻 Init the project 

```make init: afin d'installer le projet```

### ⚙️ Command list

```make help```

### 🛠 Create database and play migrations and fixtures

```make database-init```

### 🏛️ Watch JS and CSS files in development

```make npm-watch```

### 🏛️ Build JS and CSS files in production

```make npm-build```

### 📧  Messenger consume

```make messenger```


---

### **2. Fonctionnalités à ajouter**
Un portfolio doit montrer vos compétences. Voici des idées de fonctionnalités qui impressionnent les recruteurs :

#### **Section "Projets" interactive**
- **Backend :** Utilisez une API Symfony pour récupérer les données des projets depuis une base de données.
- **Frontend :** Affichez les projets dynamiquement avec React.
- **Détail d’un projet :** Ajoutez une page ou une modale détaillant chaque projet (stack, défis, résultats).

#### **Formulaire de contact**
- Créez un formulaire avec validation (frontend et backend).
- Implémentez l’envoi d’email via un service comme **SendGrid** ou **Symfony Mailer**.

#### **CV téléchargeable**
- Ajoutez un bouton permettant de télécharger votre CV au format PDF.

#### **Blog ou espace pour des articles**
- Ajoutez un blog simple pour partager des articles techniques.
- Utilisez une base de données pour gérer les articles ou un système de markdown.

#### **Mode clair/sombre**
- Montrez vos compétences en frontend en intégrant un **toggle dark/light mode**.

#### **Statistiques dynamiques**
- Ajoutez une section qui affiche des statistiques issues de GitHub ou StackOverflow via leurs API.
- Exemple : Nombre de commits, contributions open-source, projets publics, etc.

---

### **3. Améliorations techniques**
#### **Tests**
1. **Backend (Symfony)** :
   - Tests unitaires pour vos services.
   - Tests fonctionnels pour vos API.
2. **Frontend (React)** :
   - Tests des composants avec Jest et React Testing Library.
3. **End-to-end** :
   - Testez le parcours utilisateur complet avec **Cypress**.

#### **SEO et performances**
1. Ajoutez des métadonnées pour améliorer le SEO (titre, description, balises Open Graph).
2. Utilisez des outils comme **Lighthouse** pour optimiser les performances (temps de chargement, accessibilité).

#### **CI/CD**
- Configurez une pipeline CI/CD avec GitHub Actions pour :
  - Lancer des tests automatiquement à chaque push.
  - Déployer votre portfolio automatiquement sur une plateforme (exemple : **Vercel**, **AWS**, ou **Netlify**).

---

### **4. Améliorations visuelles**
#### **Design moderne**
- Si vous avez utilisé un framework CSS comme Bootstrap, essayez d’ajouter votre touche personnelle ou d'explorer **TailwindCSS**.
- Inspirez-vous de designs modernes sur **Dribbble** ou **Behance**.

#### **Animations subtiles**
- Intégrez des animations légères avec **Framer Motion** ou **GSAP**.
- Exemples :
  - Apparition progressive des sections au scroll.
  - Effet hover sur les boutons ou cartes de projet.

---

### **5. Hébergement**
Un portfolio doit être accessible en ligne. Voici vos options :
1. **Frontend + Backend combiné** :
   - Utilisez **Heroku** ou **Render** pour héberger l’application Symfony et le frontend.
2. **Frontend séparé du backend** :
   - Déployez le frontend sur **Netlify** ou **Vercel**.
   - Hébergez le backend Symfony sur un serveur dédié ou une plateforme cloud comme **Railway.app**.

---

### **6. Suivi et amélioration continue**
#### **Analytics**
- Intégrez **Google Analytics** ou **Plausible** pour suivre les visites.
- Utilisez les données pour ajuster le contenu en fonction des sections populaires.

#### **Feedback utilisateur**
- Ajoutez un formulaire pour permettre aux recruteurs de donner leur avis.

---

## **Conclusion**
Votre portfolio est déjà un bon point de départ, mais ces améliorations peuvent le transformer en un outil puissant pour attirer les recruteurs. Concentrez-vous sur :  
1. **Une documentation et une présentation soignées.**  
2. **Des fonctionnalités impressionnantes (API dynamique, formulaire de contact, statistiques).**  
3. **Une démo en ligne facile d'accès.**

Si vous voulez de l’aide pour implémenter l’une de ces fonctionnalités ou pour configurer le déploiement, dites-le-moi ! 😊
CREATE TABLE `organisers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `full_name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `website` VARCHAR(255) DEFAULT NULL,
  `bio` TEXT DEFAULT NULL,
  `logo_url` VARCHAR(500) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `venues` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `address` VARCHAR(255) NOT NULL,
  `city` VARCHAR(100) NOT NULL,
  `capacity` INT NOT NULL DEFAULT 0,
  `contact_person` VARCHAR(100) DEFAULT NULL,
  `image_url` VARCHAR(500) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `events` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `event_date` DATE NOT NULL,
  `start_time` TIME NOT NULL,
  `end_time` TIME NOT NULL,
  `venue_id` INT NOT NULL,
  `organiser_id` INT NOT NULL,
  `category` VARCHAR(100) NOT NULL,
  `image_url` VARCHAR(500) NOT NULL,
  `ticket_price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `status` VARCHAR(50) NOT NULL DEFAULT 'Upcoming',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_events_venue` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_events_organiser` FOREIGN KEY (`organiser_id`) REFERENCES `organisers` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `contact_messages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `full_name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `subject` VARCHAR(200) NOT NULL,
  `event_id` INT DEFAULT NULL,
  `message` TEXT NOT NULL,
  `submitted_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `status` VARCHAR(50) NOT NULL DEFAULT 'Unread',
  CONSTRAINT `fk_contact_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `organisers` (`id`, `full_name`, `email`, `phone`, `website`, `bio`, `logo_url`) VALUES
(1, 'Marco van Basten', 'vanbasten@milan-legends.org', '0918347265', 'https://vanbasten.football', 'Three-time Ballon d\'Or winner and legendary Dutch striker. Renowned for surgical finishing, volleys, and technical mastery at AC Milan and Ajax Amsterdam.', 'assets/p1.jpg'),
(2, 'Petr Čech', 'petr.cech@chelseafoundation.com', '0927518463', 'https://petr-cech.com', 'Legendary Czech goalkeeper and Premier League record holder with 202 clean sheets. Celebrated for leadership, reflexes, and iconic headguard protection.', 'assets/p2.jpg'),
(3, 'Lothar Matthäus', 'matthaeus@dfb-legends.de', '0946281735', 'https://lotharmatthaeus.de', '1990 World Cup-winning captain and Ballon d\'Or recipient. Germany\'s most-capped outfield player, celebrated for tactical versatility, box-to-box dominance, and leadership.', 'assets/p3.jpg'),
(4, 'Marcelo Vieira', 'marcelo@m12holding.com', '0961735284', 'https://marcelo12.com', 'Five-time UEFA Champions League winner and iconic Brazilian left-back. Celebrated for dazzling flair, samba dribbling, attacking brilliance, and decades at Real Madrid.', 'assets/p4.jpg'),
(5, 'Gareth Bale', 'bale@elevensports.wales', '0975426183', 'https://garethbale.com', 'Five-time UEFA Champions League winner and Wales all-time top scorer. Renowned for explosive pace, iconic overhead kicks, and historic trophy-winning moments with Real Madrid.', 'assets/p5.jpg'),
(6, 'Paolo Maldini', 'maldini@acmilan.com', '0983157426', 'https://paolomaldini.it', 'Five-time European champion and iconic Italian defender. Widely revered as one of the greatest defenders in football history, spending his entire legendary 25-year career at AC Milan.', 'assets/p6.jpg');

INSERT INTO `venues` (`id`, `name`, `address`, `city`, `capacity`, `contact_person`, `image_url`) VALUES
(1, 'Greenwich Innovation Grand Auditorium', '20 Cong Hoa, Bay Hien, Tan Binh District', 'Ho Chi Minh City', 380, 'Nguyen Hoang Nam', 'assets/ven1.jpg'),
(2, 'Hoa Lac High-Tech Campus Convention Hall', 'Km29 Thang Long Boulevard, Thach That', 'Ha Noi', 520, 'Tran Thi Mai', 'assets/ven2.jpg'),
(3, 'Danang Coastal Academic & Tech Center', '658 Ngo Quyen, An Hai Bac, Son Tra', 'Da Nang', 260, 'Le Quoc Bao', 'assets/ven3.jpg'),
(4, 'Saigon South Media & Design Exhibition Hall', '702 Nguyen Van Linh, Tan Phong, District 7', 'Ho Chi Minh City', 220, 'Doan Minh Thu', 'assets/ven4.jpg'),
(5, 'Mekong River Delta Creative Hub', '160 30 Thang 4 Street, An Phu, Ninh Kieu', 'Can Tho', 190, 'Vo Tan Dat', 'assets/ven5.jpg'),
(6, 'Hanoi Heritage Cultural Auditorium', '2 Pham Van Dong, Mai Dich, Cau Giay', 'Ha Noi', 420, 'Pham Duc Anh', 'assets/ven6.jpg');

INSERT INTO `events` (`id`, `title`, `description`, `event_date`, `start_time`, `end_time`, `venue_id`, `organiser_id`, `category`, `image_url`, `ticket_price`, `status`) VALUES
(1, 'Vietnam Inter-University AI & Code Hackathon 2026', 'A 48-hour competitive software hackathon gathering university students from across Vietnam. Teams will build AI-driven solutions addressing smart urban logistics, educational technology, and automated sustainability with direct mentorship from senior tech architects.', '2026-11-14', '08:30:00', '18:00:00', 1, 1, 'Technology & Code', 'assets/event1.jpg', 0.00, 'Upcoming'),
(2, 'National Computing & Web Engineering Symposium', 'Annual academic symposium for undergraduate and postgraduate researchers presenting peer-reviewed papers on distributed databases, modern PHP architectures, cloud microservices, and web accessibility standards.', '2026-11-20', '09:00:00', '16:30:00', 2, 1, 'Academic & Research', 'assets/event2.jpg', 10.00, 'Upcoming'),
(3, 'Campus Acoustic Night: Voices of Youth', 'An energetic acoustic music evening hosted by university music societies. Featuring student indie bands, classical guitar recitals, modern folk harmonies, and open-mic sessions under warm stage lighting.', '2026-11-27', '18:30:00', '21:30:00', 4, 4, 'Live Music & Arts', 'assets/event3.jpg', 0.00, 'Upcoming'),
(4, 'Sustainable Architecture & Smart Campus Exhibition', 'Graduation showcase exhibiting 3D architectural models, biophilic campus design concepts, and eco-friendly urban masterplans crafted by final-year university architecture and design students.', '2026-12-05', '10:00:00', '17:00:00', 4, 3, 'Design & Architecture', 'assets/event4.jpg', 0.00, 'Upcoming'),
(5, 'Vietnamese Cultural Heritage & Digital Archiving Forum', 'An interdisciplinary seminar examining digital preservation methods for ancient manuscripts, folklore preservation, and 3D historical artifact scanning across Vietnamese historical landmarks.', '2026-12-12', '13:30:00', '17:00:00', 6, 5, 'History & Culture', 'assets/event5.jpg', 5.00, 'Upcoming'),
(6, 'Annual University Career Expo & Tech Internship Fair', 'Meet talent acquisition directors and technical leads from top international technology companies, banks, and creative agencies. Students can attend resume workshops, mock interviews, and on-site internship screenings.', '2026-12-18', '09:00:00', '16:00:00', 1, 6, 'Career & Enterprise', 'assets/event6.jpg', 0.00, 'Upcoming'),
(7, 'Inter-Collegiate Futsal & Badminton Championship', 'Thrilling multi-university sports tournament bringing together student-athlete teams from universities across Central Vietnam for weekend group stages, quarterfinals, and medal ceremonies.', '2027-01-08', '08:00:00', '17:30:00', 3, 2, 'Sports & Wellness', 'assets/event7.jpg', 0.00, 'Upcoming'),
(8, 'Robotics & Embedded IoT Demo Day', 'Live demonstrations of autonomous delivery drones, smart agricultural IoT sensors, and computer vision robotic arms engineered by university engineering students and tech research labs.', '2027-01-15', '13:00:00', '17:30:00', 5, 1, 'Science & Nature', 'assets/event3.jpg', 0.00, 'Upcoming');

INSERT INTO `contact_messages` (`id`, `full_name`, `email`, `subject`, `event_id`, `message`, `submitted_at`, `status`) VALUES
(1, 'Sarah Jenkins', 'sarah.j@student.gre.ac.uk', 'Accessibility Inquiry for AI Hackathon', 1, 'Hello, I would like to inquire whether the Innovation Grand Auditorium in Ho Chi Minh City has wheelchair access and reserved seating for team presentations during the AI hackathon.', '2026-10-18 10:24:00', 'Unread'),
(2, 'David Chen', 'd.chen@student.gre.ac.uk', 'Student Volunteer at Career Expo', 6, 'Dear Organisers, I am a 2nd year computing student in Danang campus and would love to register as a student guide during the December Career Expo.', '2026-10-19 14:15:30', 'Unread');
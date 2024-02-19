-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 02, 2024 at 10:28 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `crud1`
--

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `client_name`, `phone_no`, `email_address`, `created_at`, `updated_at`) VALUES
(1, 'Google', ' 1800-419-0157', 'support-in@google.com', NULL, NULL),
(2, 'Microsoft', '1800-642-7676', 'support-in@microsoft.com', NULL, NULL),
(3, 'Apple', '1800-100-9009', 'support-in@apple.com', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `commented_by` bigint(20) UNSIGNED NOT NULL,
  `user` bigint(20) UNSIGNED NOT NULL,
  `task_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

CREATE TABLE `designations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `level` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `designations`
--

INSERT INTO `designations` (`id`, `created_at`, `updated_at`, `level`) VALUES
(1, NULL, NULL, 'L1A'),
(2, NULL, NULL, 'L1B');

-- --------------------------------------------------------

--
-- Table structure for table `doctypes`
--

CREATE TABLE `doctypes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `doc_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `doctypes`
--

INSERT INTO `doctypes` (`id`, `doc_type`, `created_at`, `updated_at`) VALUES
(1, 'jpeg', '2023-12-27 05:19:15', '2023-12-27 05:19:15'),
(2, 'jpg', '2023-12-27 05:19:15', '2023-12-27 05:19:15'),
(3, 'png', '2023-12-27 05:19:15', '2023-12-27 05:19:15'),
(4, 'pdf', '2023-12-27 05:19:15', '2023-12-27 05:19:15'),
(5, 'svg', '2023-12-27 05:19:15', '2023-12-27 05:19:15'),
(6, 'doc', '2023-12-27 05:19:15', '2023-12-27 05:19:15'),
(7, 'docx', '2023-12-27 05:19:15', '2023-12-27 05:19:15'),
(8, 'xls', '2023-12-27 05:19:15', '2023-12-27 05:19:15'),
(9, 'xlsx', '2023-12-27 05:19:15', '2023-12-27 05:19:15'),
(10, 'txt', '2023-12-27 05:19:15', '2023-12-27 05:19:15');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `doc_uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `doc_type_id` bigint(20) UNSIGNED NOT NULL,
  `doc_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `version` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `comments` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED NOT NULL,
  `approved_on` date NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `attachments` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `doc_uuid`, `doc_type_id`, `doc_name`, `version`, `comments`, `approved_by`, `approved_on`, `project_id`, `created_at`, `updated_at`, `attachments`) VALUES
(1, 'df927484', 9, 'abcd', '3', '<p>n cjdcjkdwnc</p>', 4, '2023-12-01', 1, '2023-12-28 04:35:54', '2024-01-02 01:14:25', 'download_image_1704137582320.png');

-- --------------------------------------------------------

--
-- Table structure for table `document_versions`
--

CREATE TABLE `document_versions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `document_id` bigint(20) UNSIGNED NOT NULL,
  `doc_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `doc_type_id` bigint(20) UNSIGNED NOT NULL,
  `comments` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED NOT NULL,
  `approved_on` date NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `attachments` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `document_versions`
--

INSERT INTO `document_versions` (`id`, `document_id`, `doc_name`, `doc_type_id`, `comments`, `approved_by`, `approved_on`, `project_id`, `version`, `created_at`, `updated_at`, `attachments`) VALUES
(1, 1, 'abcd', 9, 'n cjdcjkdwnc', 3, '2023-12-01', 1, '1', '2023-12-28 04:40:48', '2023-12-28 04:40:48', 'pmpsprint.xlsx');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `financial_years`
--

CREATE TABLE `financial_years` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('closed','open','current') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `highest_education_value`
--

CREATE TABLE `highest_education_value` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `highest_education_value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `highest_education_value`
--

INSERT INTO `highest_education_value` (`id`, `highest_education_value`, `created_at`, `updated_at`) VALUES
(1, 'High School', NULL, NULL),
(2, 'Associate Degree', NULL, NULL),
(3, 'Bachelor\'s Degree', NULL, NULL),
(4, 'Master\'s Degree', NULL, NULL),
(5, 'PhD', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_11_141916_create_roles_table', 1),
(2, '2014_10_12_000000_create_users_table', 1),
(3, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(4, '2014_10_12_200000_add_two_factor_columns_to_users_table', 1),
(5, '2019_08_19_000000_create_failed_jobs_table', 1),
(6, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(7, '2023_06_14_073141_create_highest_education_value_table', 1),
(8, '2023_06_15_042301_create_designations_table', 1),
(9, '2023_06_15_044300_create_opportunity_status_table', 1),
(10, '2023_06_15_054532_create_opportunities_table', 1),
(11, '2023_06_15_061042_create_task_status_table', 1),
(12, '2023_06_15_070626_create_project_role_table', 1),
(13, '2023_06_15_070715_create_vertical_table', 1),
(14, '2023_06_15_070716_create_profiles_table', 1),
(15, '2023_06_15_101649_create_task_types_table', 1),
(16, '2023_06_15_153129_create_technologies_table', 1),
(17, '2023_06_15_154617_create_clients_table', 1),
(18, '2023_06_16_033658_create_project_table', 1),
(19, '2023_06_17_171028_create_sessions_table', 1),
(20, '2023_06_22_174128_create_user_technologies_table', 1),
(21, '2023_06_25_080600_create_project_item_statuses_table', 1),
(22, '2023_07_04_140908_add_uuid_to_projects', 1),
(23, '2023_07_26_062540_create_project_members_table', 1),
(24, '2023_08_04_095909_create_project_task_status_table', 1),
(25, '2023_08_04_104452_create_project_task_types_table', 1),
(26, '2023_08_05_105617_create_tasks_table', 1),
(27, '2023_08_05_144100_create_task_attachments_table', 1),
(28, '2023_08_06_055239_create_comments_table', 1),
(29, '2023_08_06_182456_create_task_users_table', 1),
(30, '2023_08_11_095118_create_work_types_table', 1),
(31, '2023_08_11_095119_create_user_work_details_table', 1),
(32, '2023_11_11_183849_create_role_prices_table', 1),
(33, '2023_11_11_184502_create_worker_prices_table', 1),
(34, '2023_11_13_182705_create_financial_years_table', 1),
(35, '2023_11_13_204940_create_revenue_budgets_table', 1),
(36, '2023_12_14_100809_create_sprints_table', 1),
(37, '2023_12_14_104004_drop_assigned_to_column_from_tasks_table', 1),
(38, '2023_12_14_104314_add_assigned_and_allotted_to_columns_to_tasks_table', 2),
(39, '2023_12_19_084954_drop_assigned_to_and_allotted_to_column_from_tasks_table', 3),
(40, '2023_12_19_092036_add_assigned_and_allotted_to_columns_to_tasks_table', 3),
(41, '2023_12_20_054802_drop_assigned_to_and_allotted_to_column_from_tasks_table', 3),
(42, '2023_12_20_055031_add_assigned_to_and_allotted_to_column_to_tasks_table', 3),
(43, '2023_12_20_063345_add_project_id_to_tasks', 3),
(44, '2023_12_21_073224_drop_assigned_to_column_from_task_users_table', 4),
(45, '2023_12_21_073435_add_assigned_to_and_allotted_to_columns_to_task_users_table', 5),
(46, '2023_12_20_033440_alter_sprints_table_make_backlog_module_nullable', 6),
(47, '2023_12_20_033633_alter_sprints_table_make_task_status_id_nullable', 6),
(48, '2023_12_26_053811_modify_assigned_to_column_in_tasks_table', 6),
(49, '2023_12_26_054804_add_sprint_id_to_tasks_table', 7),
(50, '2023_12_26_112411_create_doctypes_table', 8),
(51, '2023_12_26_112906_create_documents_table', 9),
(52, '2023_12_27_051502_create_documents_table', 10),
(53, '2023_12_27_074843_create_documents_table', 11),
(54, '2023_12_27_092443_create_document_versions_table', 12),
(55, '2023_12_27_104148_create_doctypes_table', 13),
(56, '2023_12_27_104332_create_documents_table', 14),
(57, '2023_12_27_104527_create_document_versions_table', 15),
(58, '2023_12_27_072922_create_release_management_table', 16),
(59, '2023_12_28_031811_add_attachments_to_document_versions', 17),
(60, '2023_12_28_031849_add_attachments_to_documents_table', 17);

-- --------------------------------------------------------

--
-- Table structure for table `opportunities`
--

CREATE TABLE `opportunities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `opportunity_status_id` bigint(20) UNSIGNED NOT NULL,
  `proposal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `initial_stage` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `technical_stage` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `opportunities`
--

INSERT INTO `opportunities` (`id`, `opportunity_status_id`, `proposal`, `initial_stage`, `technical_stage`, `created_at`, `updated_at`) VALUES
(1, 1, 'Sample Proposal 1', 'Sample Initial Stage 1', 'Sample Technical Stage 1', NULL, NULL),
(2, 2, 'Sample Proposal 2', 'Sample Initial Stage 2', 'Sample Technical Stage 2', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `opportunity_status`
--

CREATE TABLE `opportunity_status` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_goal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `opportunity_status`
--

INSERT INTO `opportunity_status` (`id`, `project_goal`, `created_at`, `updated_at`) VALUES
(1, 'Achieved', NULL, NULL),
(2, 'Lost', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `profile_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `father_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `DOB` date NOT NULL,
  `work_location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `work_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_number` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `line_manager_id` bigint(20) UNSIGNED NOT NULL,
  `vertical_id` bigint(20) UNSIGNED NOT NULL,
  `designation_id` bigint(20) UNSIGNED NOT NULL,
  `highest_educational_qualification_id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'user.png',
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `yearly_ctc` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `profile_name`, `father_name`, `DOB`, `work_location`, `work_address`, `email`, `contact_number`, `line_manager_id`, `vertical_id`, `designation_id`, `highest_educational_qualification_id`, `image`, `user_id`, `yearly_ctc`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'Admin Papa', '1990-01-01', 'New York', '123 Main St', 'admin@admin.com', '1234567890', 1, 1, 1, 1, 'images/profiles/1704174467.jpg', 1, '1200000.00', '2023-12-21 01:56:40', '2024-01-02 00:17:47'),
(2, 'John Doe', 'John Doe Sr.', '1985-05-15', 'San Francisco', '456 Elm St', 'john@example.com', '9876543210', 1, 1, 1, 2, 'images/profiles/1704174482.jpg', 2, '200000.00', '2023-12-21 01:56:40', '2024-01-02 00:18:02'),
(3, 'Jane Smith', 'Jane Smith Sr.', '1992-08-22', 'Los Angeles', '789 Oak St', 'jane@example.com', '8765432109', 1, 1, 1, 3, 'images/profiles/1704174498.jpg', 3, '500000.00', '2023-12-21 01:56:40', '2024-01-02 00:18:18');

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Project_manager_id` bigint(20) UNSIGNED NOT NULL,
  `project_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_spoc_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_spoc_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_spoc_contact` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_startDate` date NOT NULL,
  `project_endDate` date NOT NULL,
  `project_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vertical_id` bigint(20) UNSIGNED NOT NULL,
  `technology_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `task_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `task_status_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`id`, `uuid`, `project_name`, `project_type`, `Project_manager_id`, `project_description`, `client_spoc_name`, `client_spoc_email`, `client_spoc_contact`, `project_startDate`, `project_endDate`, `project_status`, `vertical_id`, `technology_id`, `client_id`, `task_type_id`, `task_status_id`, `created_at`, `updated_at`) VALUES
(1, 'efb0ebef', 'ABC', 'Internal', 1, '<p>ABCDEFGHIJKLMNOPQRSTUVWXYZ</p>', 'SG', 'sampurnaghosh3008@gmail.com', '1234567899', '2023-11-30', '2024-01-07', 'Delay', 2, '2,3', 1, '3', '1,2', '2023-12-21 01:58:05', '2023-12-26 04:03:57'),
(2, '490bbe05', 'New Project 1', 'Internal', 1, '<p>asdfghjkl</p>', 'Arpita Nag', 'nag@gmail.com', '8894423142', '2023-12-13', '2023-12-31', 'Pending', 1, '1,2', 1, '1,2', '1,2', '2023-12-26 04:06:05', '2023-12-26 04:06:05');

-- --------------------------------------------------------

--
-- Table structure for table `project_item_statuses`
--

CREATE TABLE `project_item_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('Under discussion','Under development','In queue','Not Started','Pending','Delay') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_item_statuses`
--

INSERT INTO `project_item_statuses` (`id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Under discussion', NULL, NULL),
(2, 'Under development', NULL, NULL),
(3, 'In queue', NULL, NULL),
(4, 'Not Started', NULL, NULL),
(5, 'Pending', NULL, NULL),
(6, 'Delay', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `project_members`
--

CREATE TABLE `project_members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `project_members_id` bigint(20) UNSIGNED NOT NULL,
  `project_role_id` bigint(20) UNSIGNED NOT NULL,
  `engagement_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `duration` decimal(5,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `engagement_mode` enum('daily','weekly','monthly','yearly') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_members`
--

INSERT INTO `project_members` (`id`, `project_id`, `project_members_id`, `project_role_id`, `engagement_percentage`, `start_date`, `end_date`, `duration`, `is_active`, `engagement_mode`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, '10.00', '2023-12-08', '2023-12-13', '5.00', 1, 'daily', '2023-12-21 01:58:43', '2023-12-21 01:58:43'),
(2, 1, 1, 2, '50.00', '2023-11-30', '2023-12-10', '10.00', 1, 'daily', '2023-12-21 01:59:24', '2023-12-21 01:59:24'),
(3, 2, 2, 1, '8.00', '2023-12-21', '2023-12-24', '3.00', 1, 'daily', '2023-12-26 04:12:44', '2023-12-26 04:12:44'),
(4, 2, 3, 2, '8.00', '2023-12-20', '2023-12-25', '5.00', 1, 'daily', '2023-12-26 04:13:14', '2023-12-26 04:13:14'),
(5, 1, 3, 2, '4.00', '2023-12-02', '2023-12-07', '5.00', 1, 'daily', '2023-12-29 05:11:20', '2023-12-29 05:11:20');

--
-- Triggers `project_members`
--
DELIMITER $$
CREATE TRIGGER `calculate_end_date` BEFORE INSERT ON `project_members` FOR EACH ROW BEGIN
                IF NEW.start_date IS NOT NULL AND NEW.duration IS NOT NULL AND NEW.engagement_mode IS NOT NULL THEN
                    CASE NEW.engagement_mode
                        WHEN 'daily' THEN
                            SET NEW.end_date = DATE_ADD(NEW.start_date, INTERVAL NEW.duration DAY);
                        WHEN 'weekly' THEN
                            SET NEW.end_date = DATE_ADD(NEW.start_date, INTERVAL (NEW.duration * 5) DAY);
                        WHEN 'monthly' THEN
                            SET NEW.end_date = DATE_ADD(NEW.start_date, INTERVAL NEW.duration MONTH);
                        WHEN 'yearly' THEN
                            SET NEW.end_date = DATE_ADD(NEW.start_date, INTERVAL NEW.duration YEAR);
                    END CASE;
                END IF;
            END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `project_role`
--

CREATE TABLE `project_role` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `member_role_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_role`
--

INSERT INTO `project_role` (`id`, `member_role_type`, `created_at`, `updated_at`) VALUES
(1, 'Developer', NULL, NULL),
(2, 'Designer', NULL, NULL),
(3, 'Manager', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `project_task_status`
--

CREATE TABLE `project_task_status` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `task_status_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_task_status`
--

INSERT INTO `project_task_status` (`id`, `project_id`, `task_status_id`, `created_at`, `updated_at`) VALUES
(1, 1, 4, '2023-12-21 01:58:05', '2023-12-21 01:58:05'),
(2, 2, 1, '2023-12-26 04:06:05', '2023-12-26 04:06:05'),
(3, 2, 2, '2023-12-26 04:06:05', '2023-12-26 04:06:05');

-- --------------------------------------------------------

--
-- Table structure for table `project_task_types`
--

CREATE TABLE `project_task_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `task_type_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_task_types`
--

INSERT INTO `project_task_types` (`id`, `project_id`, `task_type_id`, `created_at`, `updated_at`) VALUES
(1, 1, 3, '2023-12-21 01:58:05', '2023-12-21 01:58:05'),
(2, 2, 1, '2023-12-26 04:06:05', '2023-12-26 04:06:05'),
(3, 2, 2, '2023-12-26 04:06:05', '2023-12-26 04:06:05');

-- --------------------------------------------------------

--
-- Table structure for table `release_management`
--

CREATE TABLE `release_management` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `release_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `release_management`
--

INSERT INTO `release_management` (`id`, `uuid`, `project_id`, `name`, `details`, `release_date`, `created_at`, `updated_at`) VALUES
(1, 'c3f08758', 2, 'RM1', 'xx gy gy uubh', '2023-12-29', '2023-12-28 00:09:35', '2023-12-28 00:09:35'),
(2, 'd9a29ef9', 1, 'RM1', 'bjbe o cnojdddddduaihjrwnfvygrrukicoxszpla;,o', '2023-12-30', '2023-12-28 02:06:21', '2023-12-28 02:06:21');

-- --------------------------------------------------------

--
-- Table structure for table `release_management_documents`
--

CREATE TABLE `release_management_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `release_management_id` bigint(20) UNSIGNED NOT NULL,
  `document_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `release_management_documents`
--

INSERT INTO `release_management_documents` (`id`, `release_management_id`, `document_path`, `created_at`, `updated_at`) VALUES
(1, 1, 'release_management_documents/CT20234346687_Application (3).pdf', '2023-12-28 00:09:35', '2023-12-28 00:09:35'),
(2, 1, 'release_management_documents/CT20234346687_Application (2).pdf', '2023-12-28 00:09:35', '2023-12-28 00:09:35'),
(3, 1, 'release_management_documents/CT20234346687_Application (1).pdf', '2023-12-28 00:09:35', '2023-12-28 00:09:35'),
(4, 1, 'release_management_documents/CT20234346687_Application.pdf', '2023-12-28 00:09:35', '2023-12-28 00:09:35'),
(5, 2, 'release_management_documents/TCS NQT Roadmap.pdf', '2023-12-28 02:06:21', '2023-12-28 02:06:21'),
(6, 2, 'release_management_documents/CT20234346687_Application (3).pdf', '2023-12-28 02:06:21', '2023-12-28 02:06:21');

-- --------------------------------------------------------

--
-- Table structure for table `revenue_budgets`
--

CREATE TABLE `revenue_budgets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `financial_year_id` bigint(20) UNSIGNED NOT NULL,
  `vertical_id` bigint(20) UNSIGNED NOT NULL,
  `budget_type` enum('weekly','monthly','quarterly','yearly') COLLATE utf8mb4_unicode_ci NOT NULL,
  `period_start` datetime NOT NULL,
  `period_end` datetime NOT NULL,
  `period_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'INR',
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', NULL, NULL),
(2, 'User', NULL, NULL),
(3, 'Manager', NULL, NULL),
(4, 'Line Manager', NULL, NULL),
(5, 'Project Manager', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role_prices`
--

CREATE TABLE `role_prices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `daily_price` decimal(10,2) NOT NULL,
  `monthly_price` decimal(10,2) NOT NULL,
  `yearly_price` decimal(10,2) NOT NULL,
  `weekly_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('xPti4oqg7KVaYYb0Bo5FfevAGS8OB9DBQtZNR4Ar', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiZ0VVOUVMMGRPRFRvQkNiRkVsQkduVm4ydlBFRUJWS2tTQU00RTQ1dCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvcHJvamVjdC8xL2FsbC10YXNrcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoyMToicGFzc3dvcmRfaGFzaF9zYW5jdHVtIjtzOjYwOiIkMnkkMTAkV1NjVHREOU55SVJBeGxrQmcvUGt1dVNPM2pVWDFaSnowSXM4djVCR1B4Qy42S3U3MkdwQ3kiO30=', 1704179278),
('yIdxZXEp8K0h93kuiSLVpEU0SapmuWdNzRIKLxmn', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTXJjMndodWVvY1ZZc0l1dm5HVUx6MEZYeG1Bek5sS2dpdTAzNFJEdiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo1MDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3Byb2plY3QvMS9yZWxlYXNlX21hbmFnZW1lbnQiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo1MDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3Byb2plY3QvMS9yZWxlYXNlX21hbmFnZW1lbnQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1704174329);

-- --------------------------------------------------------

--
-- Table structure for table `sprints`
--

CREATE TABLE `sprints` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sprint_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `backlog_module` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estimated_hrs` double(8,2) NOT NULL,
  `actual_hrs` double(8,2) NOT NULL,
  `sprint_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `current_date` date NOT NULL,
  `assign_to` bigint(20) UNSIGNED NOT NULL,
  `task_status_id` bigint(20) UNSIGNED DEFAULT NULL,
  `projects_id` bigint(20) UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sprints`
--

INSERT INTO `sprints` (`id`, `sprint_name`, `backlog_module`, `estimated_hrs`, `actual_hrs`, `sprint_status`, `current_date`, `assign_to`, `task_status_id`, `projects_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'PMP', NULL, 7.00, 7.00, 'Under development', '2023-12-29', 2, NULL, 1, 1, '2023-12-26 04:00:49', '2023-12-28 02:15:35'),
(2, 'Rice Smart', NULL, 7.00, 7.00, 'In queue', '2023-12-29', 1, NULL, 1, 1, '2023-12-26 04:07:33', '2023-12-28 01:44:23'),
(3, 'Sprint1', NULL, 5.00, 5.00, 'Not Started', '2024-01-07', 1, NULL, 1, 1, '2023-12-28 00:00:21', '2023-12-28 01:52:17'),
(14, 'Sprint5', NULL, 4.00, 5.00, 'In queue', '2023-12-06', 3, NULL, 1, 0, '2023-12-28 01:04:02', '2023-12-28 01:04:02'),
(16, 'Sprint1', NULL, 5.00, 5.00, 'Under development', '2023-12-29', 1, NULL, 1, 1, '2023-12-28 01:47:26', '2023-12-28 01:47:50'),
(17, 'Sprint1', NULL, 8.00, 8.00, 'Under development', '2023-12-29', 2, NULL, 1, 1, '2023-12-28 01:53:05', '2023-12-28 02:18:17'),
(18, 'abcd', NULL, 3.00, 3.00, 'Under discussion', '2023-12-30', 2, NULL, 1, 1, '2023-12-28 02:26:50', '2023-12-28 02:27:11'),
(19, 'dcbjhb', NULL, 4.00, 4.00, 'Under development', '2023-12-22', 2, NULL, 2, 1, '2023-12-28 02:29:56', '2023-12-28 02:38:18'),
(20, 'dcbjhbctyfuguhij', NULL, 4.00, 4.00, 'Under development', '2023-12-22', 2, NULL, 1, 1, '2023-12-28 02:32:46', '2023-12-28 02:32:56');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estimated_time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_task_status_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `assigned_to` bigint(20) UNSIGNED NOT NULL,
  `allotted_to` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sprint_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `uuid`, `title`, `priority`, `estimated_time`, `details`, `project_task_status_id`, `created_at`, `updated_at`, `assigned_to`, `allotted_to`, `project_id`, `sprint_id`) VALUES
(5, 'fdf5564c', 'Task 1', 'p1', '3', 'dfghjk', 1, NULL, NULL, 1, 'admin', 1, 1),
(6, 'fdf9564c', 'Task 2', 'p2', '5', 'dnjksnfsnfsjfn', 2, NULL, NULL, 2, 'admin', 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `task_attachments`
--

CREATE TABLE `task_attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tasks_id` bigint(20) UNSIGNED NOT NULL,
  `attachments` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_status`
--

CREATE TABLE `task_status` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task_status`
--

INSERT INTO `task_status` (`id`, `status`, `level`, `created_at`, `updated_at`) VALUES
(1, 'To do', 0, '2023-12-21 01:56:40', '2023-12-21 01:56:40'),
(2, 'Under Discussion', 1, '2023-12-21 01:56:40', '2023-12-21 01:56:40'),
(3, 'Under Design', 2, '2023-12-21 01:56:40', '2023-12-21 01:56:40'),
(4, 'In Queue', 3, '2023-12-21 01:56:40', '2023-12-21 01:56:40'),
(5, 'Under Development', 4, '2023-12-21 01:56:40', '2023-12-21 01:56:40'),
(6, 'In Progress', 5, '2023-12-21 01:56:40', '2023-12-21 01:56:40'),
(7, 'Done', 6, '2023-12-21 01:56:40', '2023-12-21 01:56:40');

-- --------------------------------------------------------

--
-- Table structure for table `task_types`
--

CREATE TABLE `task_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` int(11) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task_types`
--

INSERT INTO `task_types` (`id`, `type_name`, `level`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Feature', 0, 'Description for Feature', '2023-12-21 01:56:40', '2023-12-21 01:56:40'),
(2, 'Epic', 1, 'Description for Epic', '2023-12-21 01:56:40', '2023-12-21 01:56:40'),
(3, 'Story', 2, 'Description for Story', '2023-12-21 01:56:40', '2023-12-21 01:56:40'),
(4, 'Task', 3, 'Description for Task', '2023-12-21 01:56:40', '2023-12-21 01:56:40');

-- --------------------------------------------------------

--
-- Table structure for table `task_users`
--

CREATE TABLE `task_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `assigned_to` bigint(20) UNSIGNED NOT NULL,
  `allotted_to` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `technologies`
--

CREATE TABLE `technologies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `technology_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expertise` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `technologies`
--

INSERT INTO `technologies` (`id`, `technology_name`, `expertise`, `created_at`, `updated_at`) VALUES
(1, 'PHP', 'Intermediate', NULL, NULL),
(2, 'JavaScript', 'Advanced', NULL, NULL),
(3, 'Python', 'Beginner', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `current_team_id` bigint(20) UNSIGNED DEFAULT NULL,
  `profile_photo_path` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `is_admin`, `current_team_id`, `profile_photo_path`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@admin.com', NULL, '$2y$10$WScTtD9NyIRAxlkBg/PkuuSO3jUX1ZJz0Is8v5BGPxC.6Ku72GpCy', NULL, NULL, NULL, NULL, 1, NULL, NULL, '2023-12-21 01:56:40', '2023-12-21 01:56:40'),
(2, 'John Doe', 'john@example.com', NULL, '$2y$10$AaU1H1Fn0h4MVeFIq7.TBetbsFcHdZwhCLeM2OW7u/NA9TbydRg6e', NULL, NULL, NULL, NULL, 0, NULL, NULL, '2023-12-21 01:56:40', '2023-12-21 01:56:40'),
(3, 'Jane Smith', 'jane@example.com', NULL, '$2y$10$QM9jdQaePc8ABEcS0gzuVey59WbyoXM0/4WZY7dV3JfbbE1/SYAM.', NULL, NULL, NULL, NULL, 0, NULL, NULL, '2023-12-21 01:56:40', '2023-12-21 01:56:40');

-- --------------------------------------------------------

--
-- Table structure for table `user_technologies`
--

CREATE TABLE `user_technologies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `project_role_id` bigint(20) UNSIGNED NOT NULL,
  `technology_id` bigint(20) UNSIGNED NOT NULL,
  `details` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `years_of_experience` int(11) NOT NULL,
  `is_current_company` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_work_details`
--

CREATE TABLE `user_work_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `task_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `profile_id` bigint(20) UNSIGNED NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `project_manager_id` bigint(20) UNSIGNED NOT NULL,
  `work_type_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vertical`
--

CREATE TABLE `vertical` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vertical_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vertical_head_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vertical_head_emailId` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vertical_head_contact` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vertical`
--

INSERT INTO `vertical` (`id`, `vertical_name`, `vertical_head_name`, `vertical_head_emailId`, `vertical_head_contact`, `created_at`, `updated_at`) VALUES
(1, 'Sales', 'John Doe', 'john@example.com', '1234567890', NULL, NULL),
(2, 'Marketing', 'Jane Smith', 'jane@example.com', '9876543210', NULL, NULL),
(3, 'Full Stack', 'Bivash Kanti Pal', 'bivashkanti.pal@adamastech.in', '8981172322', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `worker_prices`
--

CREATE TABLE `worker_prices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `worker_id` bigint(20) UNSIGNED NOT NULL,
  `daily_price` decimal(10,2) NOT NULL,
  `monthly_price` decimal(10,2) NOT NULL,
  `yearly_price` decimal(10,2) NOT NULL,
  `weekly_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `work_types`
--

CREATE TABLE `work_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `work_types`
--

INSERT INTO `work_types` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Research', 'Conducting investigative activities', '2023-12-21 01:56:40', '2023-12-21 01:56:40'),
(2, 'Coding', 'Writing and testing code', '2023-12-21 01:56:40', '2023-12-21 01:56:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_commented_by_foreign` (`commented_by`),
  ADD KEY `comments_user_foreign` (`user`),
  ADD KEY `comments_task_id_foreign` (`task_id`);

--
-- Indexes for table `designations`
--
ALTER TABLE `designations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `designations_level_unique` (`level`);

--
-- Indexes for table `doctypes`
--
ALTER TABLE `doctypes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `doctypes_doc_type_unique` (`doc_type`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `documents_doc_uuid_unique` (`doc_uuid`),
  ADD KEY `documents_doc_type_id_foreign` (`doc_type_id`),
  ADD KEY `documents_approved_by_foreign` (`approved_by`),
  ADD KEY `documents_project_id_foreign` (`project_id`);

--
-- Indexes for table `document_versions`
--
ALTER TABLE `document_versions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document_versions_document_id_foreign` (`document_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `financial_years`
--
ALTER TABLE `financial_years`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `highest_education_value`
--
ALTER TABLE `highest_education_value`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `opportunities`
--
ALTER TABLE `opportunities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `opportunities_opportunity_status_id_foreign` (`opportunity_status_id`);

--
-- Indexes for table `opportunity_status`
--
ALTER TABLE `opportunity_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `profiles_email_unique` (`email`),
  ADD UNIQUE KEY `profiles_contact_number_unique` (`contact_number`),
  ADD KEY `profiles_line_manager_id_foreign` (`line_manager_id`),
  ADD KEY `profiles_vertical_id_foreign` (`vertical_id`),
  ADD KEY `profiles_designation_id_foreign` (`designation_id`),
  ADD KEY `profiles_highest_educational_qualification_id_foreign` (`highest_educational_qualification_id`),
  ADD KEY `profiles_user_id_foreign` (`user_id`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `project_uuid_unique` (`uuid`),
  ADD KEY `project_project_manager_id_foreign` (`Project_manager_id`),
  ADD KEY `project_vertical_id_foreign` (`vertical_id`),
  ADD KEY `project_client_id_foreign` (`client_id`);

--
-- Indexes for table `project_item_statuses`
--
ALTER TABLE `project_item_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_members`
--
ALTER TABLE `project_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_members_project_id_foreign` (`project_id`),
  ADD KEY `project_members_project_members_id_foreign` (`project_members_id`),
  ADD KEY `project_members_project_role_id_foreign` (`project_role_id`);

--
-- Indexes for table `project_role`
--
ALTER TABLE `project_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_task_status`
--
ALTER TABLE `project_task_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_task_status_project_id_foreign` (`project_id`),
  ADD KEY `project_task_status_task_status_id_foreign` (`task_status_id`);

--
-- Indexes for table `project_task_types`
--
ALTER TABLE `project_task_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_task_types_project_id_foreign` (`project_id`),
  ADD KEY `project_task_types_task_type_id_foreign` (`task_type_id`);

--
-- Indexes for table `release_management`
--
ALTER TABLE `release_management`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `release_management_uuid_unique` (`uuid`),
  ADD KEY `release_management_project_id_foreign` (`project_id`);

--
-- Indexes for table `release_management_documents`
--
ALTER TABLE `release_management_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `release_management_documents_release_management_id_foreign` (`release_management_id`);

--
-- Indexes for table `revenue_budgets`
--
ALTER TABLE `revenue_budgets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `revenue_budgets_financial_year_id_foreign` (`financial_year_id`),
  ADD KEY `revenue_budgets_vertical_id_foreign` (`vertical_id`),
  ADD KEY `revenue_budgets_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_prices`
--
ALTER TABLE `role_prices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_prices_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `sprints`
--
ALTER TABLE `sprints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sprints_assign_to_foreign` (`assign_to`),
  ADD KEY `sprints_task_status_id_foreign` (`task_status_id`),
  ADD KEY `sprints_projects_id_foreign` (`projects_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tasks_uuid_unique` (`uuid`),
  ADD KEY `tasks_project_task_status_id_foreign` (`project_task_status_id`),
  ADD KEY `tasks_project_id_foreign` (`project_id`),
  ADD KEY `tasks_assigned_to_foreign` (`assigned_to`),
  ADD KEY `tasks_sprint_id_foreign` (`sprint_id`);

--
-- Indexes for table `task_attachments`
--
ALTER TABLE `task_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_attachments_tasks_id_foreign` (`tasks_id`);

--
-- Indexes for table `task_status`
--
ALTER TABLE `task_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_types`
--
ALTER TABLE `task_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_users`
--
ALTER TABLE `task_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_users_task_id_foreign` (`task_id`),
  ADD KEY `task_users_assigned_to_foreign` (`assigned_to`),
  ADD KEY `task_users_allotted_to_foreign` (`allotted_to`);

--
-- Indexes for table `technologies`
--
ALTER TABLE `technologies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_technologies`
--
ALTER TABLE `user_technologies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_technologies_user_id_foreign` (`user_id`),
  ADD KEY `user_technologies_project_role_id_foreign` (`project_role_id`),
  ADD KEY `user_technologies_technology_id_foreign` (`technology_id`);

--
-- Indexes for table `user_work_details`
--
ALTER TABLE `user_work_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_work_details_project_id_foreign` (`project_id`),
  ADD KEY `user_work_details_task_id_foreign` (`task_id`),
  ADD KEY `user_work_details_profile_id_foreign` (`profile_id`),
  ADD KEY `user_work_details_project_manager_id_foreign` (`project_manager_id`),
  ADD KEY `user_work_details_work_type_id_foreign` (`work_type_id`);

--
-- Indexes for table `vertical`
--
ALTER TABLE `vertical`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `worker_prices`
--
ALTER TABLE `worker_prices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `worker_prices_worker_id_foreign` (`worker_id`);

--
-- Indexes for table `work_types`
--
ALTER TABLE `work_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `designations`
--
ALTER TABLE `designations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `doctypes`
--
ALTER TABLE `doctypes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `document_versions`
--
ALTER TABLE `document_versions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `financial_years`
--
ALTER TABLE `financial_years`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `highest_education_value`
--
ALTER TABLE `highest_education_value`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `opportunities`
--
ALTER TABLE `opportunities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `opportunity_status`
--
ALTER TABLE `opportunity_status`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `project_item_statuses`
--
ALTER TABLE `project_item_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `project_members`
--
ALTER TABLE `project_members`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `project_role`
--
ALTER TABLE `project_role`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `project_task_status`
--
ALTER TABLE `project_task_status`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `project_task_types`
--
ALTER TABLE `project_task_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `release_management`
--
ALTER TABLE `release_management`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `release_management_documents`
--
ALTER TABLE `release_management_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `revenue_budgets`
--
ALTER TABLE `revenue_budgets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `role_prices`
--
ALTER TABLE `role_prices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sprints`
--
ALTER TABLE `sprints`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `task_attachments`
--
ALTER TABLE `task_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_status`
--
ALTER TABLE `task_status`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `task_types`
--
ALTER TABLE `task_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `task_users`
--
ALTER TABLE `task_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `technologies`
--
ALTER TABLE `technologies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_technologies`
--
ALTER TABLE `user_technologies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_work_details`
--
ALTER TABLE `user_work_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vertical`
--
ALTER TABLE `vertical`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `worker_prices`
--
ALTER TABLE `worker_prices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `work_types`
--
ALTER TABLE `work_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_commented_by_foreign` FOREIGN KEY (`commented_by`) REFERENCES `profiles` (`id`),
  ADD CONSTRAINT `comments_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`),
  ADD CONSTRAINT `comments_user_foreign` FOREIGN KEY (`user`) REFERENCES `profiles` (`id`);

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `project_members` (`id`),
  ADD CONSTRAINT `documents_doc_type_id_foreign` FOREIGN KEY (`doc_type_id`) REFERENCES `doctypes` (`id`),
  ADD CONSTRAINT `documents_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`);

--
-- Constraints for table `document_versions`
--
ALTER TABLE `document_versions`
  ADD CONSTRAINT `document_versions_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `opportunities`
--
ALTER TABLE `opportunities`
  ADD CONSTRAINT `opportunities_opportunity_status_id_foreign` FOREIGN KEY (`opportunity_status_id`) REFERENCES `opportunity_status` (`id`);

--
-- Constraints for table `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `profiles_designation_id_foreign` FOREIGN KEY (`designation_id`) REFERENCES `designations` (`id`),
  ADD CONSTRAINT `profiles_highest_educational_qualification_id_foreign` FOREIGN KEY (`highest_educational_qualification_id`) REFERENCES `highest_education_value` (`id`),
  ADD CONSTRAINT `profiles_line_manager_id_foreign` FOREIGN KEY (`line_manager_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `profiles_vertical_id_foreign` FOREIGN KEY (`vertical_id`) REFERENCES `vertical` (`id`);

--
-- Constraints for table `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `project_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `project_project_manager_id_foreign` FOREIGN KEY (`Project_manager_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `project_vertical_id_foreign` FOREIGN KEY (`vertical_id`) REFERENCES `vertical` (`id`);

--
-- Constraints for table `project_members`
--
ALTER TABLE `project_members`
  ADD CONSTRAINT `project_members_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `project_members_project_members_id_foreign` FOREIGN KEY (`project_members_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `project_members_project_role_id_foreign` FOREIGN KEY (`project_role_id`) REFERENCES `project_role` (`id`);

--
-- Constraints for table `project_task_status`
--
ALTER TABLE `project_task_status`
  ADD CONSTRAINT `project_task_status_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_task_status_task_status_id_foreign` FOREIGN KEY (`task_status_id`) REFERENCES `task_status` (`id`);

--
-- Constraints for table `project_task_types`
--
ALTER TABLE `project_task_types`
  ADD CONSTRAINT `project_task_types_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_task_types_task_type_id_foreign` FOREIGN KEY (`task_type_id`) REFERENCES `task_types` (`id`);

--
-- Constraints for table `release_management`
--
ALTER TABLE `release_management`
  ADD CONSTRAINT `release_management_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`);

--
-- Constraints for table `release_management_documents`
--
ALTER TABLE `release_management_documents`
  ADD CONSTRAINT `release_management_documents_release_management_id_foreign` FOREIGN KEY (`release_management_id`) REFERENCES `release_management` (`id`);

--
-- Constraints for table `revenue_budgets`
--
ALTER TABLE `revenue_budgets`
  ADD CONSTRAINT `revenue_budgets_financial_year_id_foreign` FOREIGN KEY (`financial_year_id`) REFERENCES `financial_years` (`id`),
  ADD CONSTRAINT `revenue_budgets_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `revenue_budgets` (`id`),
  ADD CONSTRAINT `revenue_budgets_vertical_id_foreign` FOREIGN KEY (`vertical_id`) REFERENCES `vertical` (`id`);

--
-- Constraints for table `role_prices`
--
ALTER TABLE `role_prices`
  ADD CONSTRAINT `role_prices_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `project_role` (`id`);

--
-- Constraints for table `sprints`
--
ALTER TABLE `sprints`
  ADD CONSTRAINT `sprints_assign_to_foreign` FOREIGN KEY (`assign_to`) REFERENCES `project_members` (`id`),
  ADD CONSTRAINT `sprints_projects_id_foreign` FOREIGN KEY (`projects_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `sprints_task_status_id_foreign` FOREIGN KEY (`task_status_id`) REFERENCES `task_status` (`id`);

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `project_members` (`id`),
  ADD CONSTRAINT `tasks_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tasks_project_task_status_id_foreign` FOREIGN KEY (`project_task_status_id`) REFERENCES `project_task_status` (`id`),
  ADD CONSTRAINT `tasks_sprint_id_foreign` FOREIGN KEY (`sprint_id`) REFERENCES `sprints` (`id`);

--
-- Constraints for table `task_attachments`
--
ALTER TABLE `task_attachments`
  ADD CONSTRAINT `task_attachments_tasks_id_foreign` FOREIGN KEY (`tasks_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `task_users`
--
ALTER TABLE `task_users`
  ADD CONSTRAINT `task_users_allotted_to_foreign` FOREIGN KEY (`allotted_to`) REFERENCES `project_members` (`id`),
  ADD CONSTRAINT `task_users_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `project_members` (`id`),
  ADD CONSTRAINT `task_users_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_technologies`
--
ALTER TABLE `user_technologies`
  ADD CONSTRAINT `user_technologies_project_role_id_foreign` FOREIGN KEY (`project_role_id`) REFERENCES `project_role` (`id`),
  ADD CONSTRAINT `user_technologies_technology_id_foreign` FOREIGN KEY (`technology_id`) REFERENCES `technologies` (`id`),
  ADD CONSTRAINT `user_technologies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_work_details`
--
ALTER TABLE `user_work_details`
  ADD CONSTRAINT `user_work_details_profile_id_foreign` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_work_details_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_work_details_project_manager_id_foreign` FOREIGN KEY (`project_manager_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_work_details_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_work_details_work_type_id_foreign` FOREIGN KEY (`work_type_id`) REFERENCES `work_types` (`id`);

--
-- Constraints for table `worker_prices`
--
ALTER TABLE `worker_prices`
  ADD CONSTRAINT `worker_prices_worker_id_foreign` FOREIGN KEY (`worker_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

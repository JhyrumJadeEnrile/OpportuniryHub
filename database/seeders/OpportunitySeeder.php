<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\ProviderProfile;
use App\Models\Opportunity;
use App\Models\Application;

class OpportunitySeeder extends Seeder
{
    public function run(): void
    {
        // Create sample students
        $studentEmails = [
            'student1@tup.edu.ph',
            'student2@tup.edu.ph',
            'student3@tup.edu.ph',
            'student4@tup.edu.ph',
            'student5@tup.edu.ph',
        ];

        $studentNames = [
            'Maria Santos',
            'Juan Dela Cruz',
            'Anna Garcia',
            'Miguel Lopez',
            'Rosa Mendoza',
        ];

        $courses = [
            'Bachelor of Science in Information Technology',
            'Bachelor of Science in Civil Engineering',
            'Bachelor of Science in Business Administration',
            'Bachelor of Science in Electrical Engineering',
            'Bachelor of Science in Accountancy',
        ];

        foreach ($studentEmails as $index => $email) {
            $user = User::create([
                'name' => $studentNames[$index],
                'email' => $email,
                'password' => bcrypt('password123'),
                'role' => 'student',
                'phone' => '09' . str_pad($index, 9, '0', STR_PAD_LEFT),
            ]);

            StudentProfile::create([
                'user_id' => $user->id,
                'student_id' => '2024' . str_pad($index + 1, 5, '0', STR_PAD_LEFT),
                'course' => $courses[$index],
                'year_level' => ['1st Year', '2nd Year', '3rd Year', '4th Year', '1st Year'][$index],
                'gpa' => 3.5 + ($index * 0.1),
                'skills' => 'PHP,Laravel,JavaScript,HTML,CSS,MySQL',
                'interests' => 'Web Development,Startups,Tech Innovation',
                'university' => 'TUP-Taguig',
                'graduation_date' => now()->addYears(2)->toDateString(),
            ]);
        }

        // Create sample providers
        $providers = [
            [
                'name' => 'TechCorp Philippines',
                'company_name' => 'TechCorp Philippines',
                'email' => 'hr@techcorp.ph',
                'industry' => 'Information Technology',
                'website' => 'https://techcorp.ph',
                'description' => 'Leading IT solutions provider in Southeast Asia',
            ],
            [
                'name' => 'DBM - Department of Budget and Management',
                'company_name' => 'DBM - Scholarship Program',
                'email' => 'scholars@dbm.gov.ph',
                'industry' => 'Government',
                'website' => 'https://dbm.gov.ph',
                'description' => 'Official scholarship provider for Philippine government workers',
            ],
            [
                'name' => 'Asia Pacific University',
                'company_name' => 'Asia Pacific University',
                'email' => 'international@apu.edu.ph',
                'industry' => 'Education',
                'website' => 'https://apu.edu.ph',
                'description' => 'Leading private university offering exchange programs',
            ],
            [
                'name' => 'ManpowerGroup',
                'company_name' => 'ManpowerGroup Philippines',
                'email' => 'recruitment@manpower.com.ph',
                'industry' => 'Human Resources',
                'website' => 'https://manpower.com.ph',
                'description' => 'Global employment solutions provider',
            ],
        ];

        foreach ($providers as $providerData) {
            $user = User::create([
                'name' => $providerData['name'],
                'email' => $providerData['email'],
                'password' => bcrypt('password123'),
                'role' => 'provider',
            ]);

            ProviderProfile::create([
                'user_id' => $user->id,
                'company_name' => $providerData['company_name'],
                'company_description' => $providerData['description'],
                'industry' => $providerData['industry'],
                'website' => $providerData['website'],
                'city' => 'Manila',
                'country' => 'Philippines',
                'is_verified' => true,
                'verified_at' => now(),
            ]);
        }

        // Create sample opportunities
        $opportunitiesData = [
            // Scholarships
            [
                'type' => 'scholarship',
                'title' => 'Merit-Based Undergraduate Scholarship 2024',
                'description' => 'Comprehensive scholarship covering tuition fees and allowance for high-achieving students. Requirements: 3.5+ GPA, demonstrated leadership. This scholarship is designed to support exceptional students pursuing their dreams at premier institutions.',
                'salary_range' => '₱50,000 - ₱150,000 per year',
                'duration' => '1 academic year (renewable)',
                'positions' => 10,
                'requirements' => 'Minimum 3.5 GPA, Passing of interview, Proof of financial need',
                'benefits' => 'Full tuition coverage, Monthly allowance, Book allowance, Research support',
                'provider_index' => 1,
                'location' => 'Metro Manila',
                'is_featured' => true,
            ],
            [
                'type' => 'scholarship',
                'title' => 'Government Service Scholarship Program',
                'description' => 'Opportunity to serve the government while pursuing your studies. Ideal for students committed to public service and nation-building. Post-graduation employment guaranteed.',
                'salary_range' => 'Full support + monthly stipend',
                'duration' => '1-4 years',
                'positions' => 50,
                'requirements' => 'Filipino citizen, Good moral character, Willing to work for government',
                'benefits' => 'Full scholarship, Monthly allowance, Guaranteed employment, Medical insurance',
                'provider_index' => 1,
                'location' => 'National Capital Region',
                'is_featured' => true,
            ],

            // Internships
            [
                'type' => 'internship',
                'title' => 'Summer Software Development Internship',
                'description' => 'Join our innovative tech team for a hands-on summer internship. Work on real projects using latest technologies. Perfect for IT and CS students.',
                'salary_range' => '₱15,000 - ₱20,000 per month',
                'duration' => '3 months',
                'positions' => 8,
                'requirements' => 'Currently enrolled in IT or CS course, Familiar with PHP/Laravel or JavaScript',
                'benefits' => 'Competitive stipend, Mentorship, Technical training, Possible conversion to employment',
                'provider_index' => 0,
                'location' => 'BGC, Taguig',
                'is_featured' => false,
            ],
            [
                'type' => 'internship',
                'title' => 'University Exchange Internship Program',
                'description' => 'International exposure with our university partner network across Asia. Gain global experience while contributing to research projects.',
                'salary_range' => 'Sponsored travel + monthly allowance',
                'duration' => '2-4 months',
                'positions' => 20,
                'requirements' => 'Minimum 2.75 GPA, English proficiency, Open to any course',
                'benefits' => 'Flight coverage, Accommodation, Daily allowance, Certificate of completion',
                'provider_index' => 2,
                'location' => 'Various (Asia-Pacific)',
                'is_featured' => false,
            ],

            // Training
            [
                'type' => 'training',
                'title' => 'AWS Cloud Certification Bootcamp',
                'description' => 'Intensive training program to prepare for AWS Certified Solutions Architect certification. Industry-recognized credential for IT professionals.',
                'salary_range' => 'Free training + certification exam coverage',
                'duration' => '6 weeks',
                'positions' => 30,
                'requirements' => 'Basic IT knowledge, English proficiency',
                'benefits' => 'Free training materials, Exam voucher, Job placement assistance, Lifetime access to resources',
                'provider_index' => 0,
                'location' => 'Online / Manila',
                'is_featured' => true,
            ],
            [
                'type' => 'training',
                'title' => 'HR Excellence Development Program',
                'description' => 'Comprehensive HR training covering recruitment, employee relations, and organizational development. Certificate recognized nationally.',
                'salary_range' => 'Free program',
                'duration' => '8 weeks',
                'positions' => 25,
                'requirements' => 'Interested in HR field, Good communication skills',
                'benefits' => 'Free certification, Job fair access, Industry connections, Career guidance',
                'provider_index' => 3,
                'location' => 'Manila',
                'is_featured' => false,
            ],

            // Jobs
            [
                'type' => 'job',
                'title' => 'Junior Web Developer (Full-Time)',
                'description' => 'Join our growing team as a Junior Web Developer. Work on modern web applications using PHP, Laravel, and Vue.js. Great opportunity for recent graduates.',
                'salary_range' => '₱25,000 - ₱35,000 per month',
                'duration' => 'Permanent',
                'positions' => 5,
                'requirements' => 'BS in CS/IT or equivalent, Knowledge of PHP/Laravel, HTML/CSS/JavaScript, Problem-solving skills',
                'benefits' => 'Competitive salary, Health insurance, Training allowance, Performance bonus, Career growth',
                'provider_index' => 0,
                'location' => 'BGC, Taguig',
                'is_featured' => true,
            ],
            [
                'type' => 'job',
                'title' => 'Administrative Officer',
                'description' => 'Government position for administrative support. Stable career in public service. Open to all graduates with strong organizational skills.',
                'salary_range' => '₱18,000 - ₱25,000 per month',
                'duration' => 'Permanent (Civil Service)',
                'positions' => 15,
                'requirements' => 'College graduate, Filipino citizen, Passed civil service exam, Good moral character',
                'benefits' => 'Government salary, Pension scheme, Government ID, Health insurance, Leave benefits',
                'provider_index' => 1,
                'location' => 'NCR',
                'is_featured' => false,
            ],
        ];

        $providers_list = ProviderProfile::all();

        foreach ($opportunitiesData as $data) {
            $provider = $providers_list[$data['provider_index']];
            
            Opportunity::create([
                'provider_id' => $provider->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'type' => $data['type'],
                'status' => 'active',
                'location' => $data['location'],
                'salary_range' => $data['salary_range'],
                'duration' => $data['duration'],
                'positions_available' => $data['positions'],
                'application_deadline' => now()->addDays(rand(7, 90))->toDateString(),
                'requirements' => $data['requirements'],
                'benefits' => $data['benefits'],
                'course_eligibility' => 'All courses',
                'tags' => implode(',', array_slice(explode(' ', strtolower($data['title'])), 0, 3)),
                'is_featured' => $data['is_featured'],
                'published_at' => now(),
            ]);
        }

        // Create sample applications
        $students = User::where('role', 'student')->get();
        $opportunities = Opportunity::all();

        foreach ($students->take(3) as $student) {
            foreach ($opportunities->random(3) as $opportunity) {
                if (Application::where('student_id', $student->id)->where('opportunity_id', $opportunity->id)->doesntExist()) {
                    Application::create([
                        'student_id' => $student->id,
                        'opportunity_id' => $opportunity->id,
                        'status' => ['submitted', 'under_review', 'shortlisted'][rand(0, 2)],
                        'cover_letter' => 'I am very interested in this opportunity and believe I have the skills and experience needed to excel in this role. I am eager to contribute to your organization and grow professionally.',
                        'submitted_at' => now()->subDays(rand(1, 30)),
                    ]);
                }
            }
        }

        echo "✅ Sample data seeded successfully!\n";
        echo "Created: 5 students, 4 providers, 8 opportunities, 9 applications\n";
    }
}

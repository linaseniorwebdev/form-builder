<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Form;
use App\School;

class FormController extends Controller
{
    public function index()
    {
        return Form::all();
    }
 
    public function show($id)
    {
        return Form::find($id);
    }

    public function school(School $school)
    {
    	$school = Form::where('school_id', $school->id)->get();
    	return $school;
    }

    public function store(Request $request)
    {
    	
        $form = new Form;
        $form->name = $request->name;
        $form->form_options = $request->form_options;
        $form->hidden_props = "hp";
        $form->school_id = $request->school_id;
        $form->steps = $request->steps;
        $form->save();
		

        return ['message' => 'Form Saved in DB'];
    }

    public function update(Request $request, Form $form)
    {
    	$formupdated = Form::findOrFail($form->id);
    	$formupdated->name = $request->name;
        $formupdated->form_options = $request->form_options;
    	$formupdated->school_id = $request->school_id;
    	$formupdated->steps = $request->steps;
    	$formupdated->hidden_props = "hp";
    	$formupdated->save();

        return ['message' => 'Form Updated in DB'];
    }

    // TODO - Move Out of Controller - Can be deleted, now pulling from API
    public function default() 
    {

        $default = [
            'meets_laurus_minimum_at_step' => 2,
            'rankers' => [
                'laurus'
            ],
            'steps' => [
                1 => [
                    'name__first' => [
                        'polsone__required' => true,
                        'polsone__sometimes' => true,
                        'polsone__validation' => [
                            'not_in:kings,ley'
                        ],
                        'frontend__toggles' => [
                            [
                                'toggle' => 'if',
                                'value' => 'kingsley',
                                'fields' => [
                                    'name__last',
                                ]
                            ]
                        ],
                        'frontend__label' => 'First Name',
                        'frontend__value' => '',
                        'frontend__placeholder' => 'Placeholder',
                        'frontend__help_text' => 'Your given name',
                        'frontend__heading' => 'What\'s your first name?',
                        'frontend__section' => 'Personal Info',
                    ],
                    'name__last' => [
                        'polsone__required' => true,
                        'polsone__sometimes' => false,
                        'polsone__validation' => [
                            'not_in:kings,ley'
                        ],
                        'frontend__toggles' => [
                            
                        ],
                        'frontend__label' => 'Last Name',
                        'frontend__value' => '',
                        'frontend__placeholder' => 'Last Name',
                        'frontend__help_text' => 'Your surname',
                        'frontend__heading' => 'What\'s your last name?',
                        'frontend__section' => 'Personal Info',
                    ],
                ],
                2 => [
                    'contact__email' => [
                        'polsone__required' => false,
                        'polsone__sometimes' => false,
                        'polsone__validation' => [
                            
                        ],
                        'frontend__toggles' => [
                            
                        ],
                        'frontend__label' => 'Email',
                        'frontend__value' => '',
                        'frontend__placeholder' => 'Email Adresss',
                        'frontend__help_text' => 'Your email',
                        'frontend__heading' => 'What\'s your email?',
                        'frontend__section' => 'Step 2',
                    ],
                ]
            ],
        ];

        $default = json_encode($default, JSON_UNESCAPED_UNICODE);
        return $default;

    }

    //TODO - Move out of Controller - Can be deleted, now pulling from API
    public function fields()
    {
        $fields = [
            'name__prefix' => 'Name Prefix (Mr, Miss, Mrs, etc.)',
            'name__first' => 'First Name',
            'name__middle' => 'Middle Name(s)',
            'name__last' => 'Last Name',
            'name__full' => 'Full Name',
            'contact__email' => 'Email Address',
            'contact__phone' => 'Phone Number',
            'contact__phone_ext' => 'Phone Number Extension',
            'program__code' => 'Program Code',
            'program__access_code' => 'Access Code',
            'confirmation_page' => 'Confirmation Page',
            'external__reference_number' => 'External Reference Number',
            'external__lead_id' => 'External Lead ID',
            'external__campaign_code' => 'External Campaign Code',
            'external__script_id' => 'External Script ID',
            'program__code_2' => 'Program Code 2',
            'program__code_3' => 'Program Code 3',
            'program__code_4' => 'Program Code 4',
            'program__code_5' => 'Program Code 5',
            'program__code_6' => 'Program Code 6',
            'program__code_7' => 'Program Code 7',
            'program__code_8' => 'Program Code 8',
            'program__code_9' => 'Program Code 9',
            'program__code_10' => 'Program Code 10',
            'social__twitter' => 'Twitter Account',
            'social__linkedin' => 'LinkedIn Account',
            'social__facebook' => 'Facebook Account',
            'contact__preferred' => 'Preferred Contact Method',
            'address__full' => 'Full Address',
            'address__line_1' => 'Address Line 1',
            'address__line_2' => 'Address Line 2',
            'address__city' => 'City',
            'address__state' => 'State',
            'address__county' => 'County',
            'address__country' => 'Country',
            'address__postal_code' => 'Postal Code',
            'professional__goals' => 'Professional Goals',
            'profressional__employee_id' => 'Employee ID',
            'professional__current_employer' => 'Current Employer',
            'professional__current_position' => 'Current Position',
            'professional__level' => 'Professional Level',
            'professional__memberships' => 'Professional Memberships',
            'professional__your_role_or_environment' => 'Your Role and/or Environment',
            'professional__your_role_or_environment--other' => 'Your Role and/or Environment (other)',
            'program__start_term_season' => 'Start Term Season',
            'graduate__highest_degree_earned' => 'Highest Graduate Degree Earned',
            'undergraduate__student_type' => 'Undergraduate Student Type',
            'undergraduate__high_school_attended' => 'High School Attended',
            'undergraduate__high_school_attended_name' => 'High School Name',
            'undergraduate__high_school_attended_address' => 'High School Address',
            'graduate__earned_degree_name' => 'Degree Name',
            'graduate__last_school_attended' => 'Last Graduate School Attended',
            'graduate__last_school_name' => 'Last Graduate School Name',
            'graduate__last_school_address' => 'Last Graduate School Address',
            'back_to_school_motivation' => 'Back to School Motivation',
            'back_to_school_motivators__1' => '1st Back to School Motivation',
            'back_to_school_motivators__1--other' => 'Other 1st Back to School Motivation',
            'back_to_school_motivators__2' => '2nd Back to School Motivation',
            'back_to_school_motivators__2--other' => 'Other 2nd Back to School Motivation',
            'back_to_school_motivators__3' => '3rd Back to School Motivation',
            'back_to_school_motivators__3--other' => 'Other 3rd Back to School Motivation',
            'notes' => 'Notes',
            'misc__question_1' => 'Miscellaneous Question 1',
            'misc__answer_1' => 'Answer 1 ',
            'misc__question_2' => 'Miscellaneous Question 2',
            'misc__answer_2' => 'Answer 2 ',
            'misc__question_3' => 'Miscellaneous Question 3',
            'misc__answer_3' => 'Answer 3 ',
            'misc__question_4' => 'Miscellaneous Question 4',
            'misc__answer_4' => 'Answer 4 ',
            'misc__question_5' => 'Miscellaneous Question 5',
            'misc__answer_5' => 'Answer 5 ',
            'analytics__keyword' => 'Keyword',
            'analytics__keywordmt' => 'Keyword MT',
            'analytics__google_client_id' => 'Google Analytics Client ID',
            'analytics__google_tracker_ids' => 'Google Analytics Tracker Id(s)',
            'analytics__utm_medium' => 'UTM Medium',
            'analytics__utm_source' => 'UTM Source',
            'analytics__utm_campaign' => 'UTM Campaign',
            'analytics_misc_1' => 'Analytics 1',
            'analytics_misc_2' => 'Analytics 2',
            'analytics_misc_3' => 'Analytics 3',
            'program__start_term_year' => 'Start Term Year',
            'undergraduate__high_school_graduation_year' => 'High School Graduation Year',
            'graduate__last_graduation_year' => 'Last Graduate School Graduation Year',
            'date__of_birth' => 'Date of Birth',
            'undergraduate__transfer_credits' => 'Transfer Credits',
            'professional__work_experience_length' => 'Work Experience (in years)',
            'professional__unrelated_work_experience_length' => 'Unrelated Work Experience (in years)',
            'undergraduate__high_school_gpa' => 'High School GPA',
            'graduate__last_gpa' => 'Graduate GPA',
            'flag__is_alumni' => 'School Alumni',
            'flag__is_international_student' => 'International Student',
            'flag__is_military_veteran_benefits_eligible' => 'Eligible For Military Assistance',
            'flag__is_financial_aid_eligible' => 'Eligible For Financial Aid',
            'flag__has_taken_online_courses_previously' => 'Previously Taken Online Courses',
            'undergraduate__graduated_high_school' => 'Graduated High School',
            'undergraduate__earned_ged_hiset_certficiate' => 'Earned GED/HiSET Certificate',
            'graduate__earned_degree' => 'Highest Degree Earned',
            'graduate__earned_degree--doctorate' => 'Earned Doctorate',
            'graduate__earned_degree--masters' => 'Earned Masters',
            'graduate__earned_degree--bachelors' => 'Earned Bachelors',
            'graduate__earned_degree--associates' => 'Earned Associates',
            'file__resume' => 'Resume',
            'file__cover_letter' => 'Cover Letter',
            'file__misc' => 'File',
            'file__resume_url' => 'Resume URL',
            'file__cover_letter_url' => 'Cover Letter URL',
            'file__misc_url' => 'File URL',
            'marketing__opt_in' => 'Opt In to Receive Marketing Info (via sms and/or email)',
            'analytics__goal_tracker' => 'Goal Tracker',
            'timezone' => 'Timezone',
            'professional__direct_reports_number' => 'Number of Direct Reports',
            'professional__budgetary_responsibility' => 'Budgetary Responsibility',
            'marketing__financial_aid_info' => 'Interested in Receiving Financial Aid Information',
            'scale5__healthcare__aca_impact' => 'Impact of ACA in your Role',
            'link__confirmation_page' => 'Confirmation Page',
        ];

        return $fields;
    }

}

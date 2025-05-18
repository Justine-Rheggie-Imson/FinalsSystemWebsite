<?php
// Debug the incoming condition parameter
$condition = isset($_GET['condition']) ? strtolower(trim($_GET['condition'])) : 'unknown';

// Debug output
error_log("Received condition parameter: " . $condition);

$details = [
    'arthritis' => [
        'title' => 'Arthritis',
        'description' => 'Arthritis is inflammation of one or more joints, causing pain and stiffness that can worsen with age.',
        'symptoms' => [
            'Joint pain and stiffness',
            'Swelling and tenderness',
            'Reduced range of motion',
            'Warmth and redness around joints',
            'Morning stiffness'
        ],
        'causes' => [
            'Age-related wear and tear',
            'Autoimmune disorders',
            'Joint injury or infection',
            'Genetic factors',
            'Obesity and excess weight'
        ],
        'treatments' => [
            'Pain medications',
            'Physical therapy',
            'Joint protection techniques',
            'Exercise and weight management',
            'Surgery in severe cases'
        ],
        'prevention' => [
            'Maintain healthy weight',
            'Regular exercise',
            'Protect joints from injury',
            'Eat a balanced diet',
            'Stay active'
        ],
        'specialties' => [
            'Rheumatologist',
            'Orthopedic Surgeon',
            'Physical Therapist',
            'Pain Management Specialist',
            'Primary Care Physician'
        ]
    ],
    'backpain' => [
        'title' => 'Back Pain',
        'description' => 'Back pain is a common condition that can range from a dull, constant ache to a sudden, sharp pain.',
        'symptoms' => [
            'Muscle ache',
            'Shooting or stabbing pain',
            'Pain that radiates down the leg',
            'Limited flexibility or range of motion',
            'Inability to stand straight'
        ],
        'causes' => [
            'Muscle or ligament strain',
            'Bulging or ruptured disks',
            'Arthritis',
            'Osteoporosis',
            'Poor posture'
        ],
        'treatments' => [
            'Pain medications',
            'Physical therapy',
            'Exercise and stretching',
            'Heat/cold therapy',
            'Massage therapy'
        ],
        'prevention' => [
            'Regular exercise',
            'Maintain proper posture',
            'Lift objects properly',
            'Maintain healthy weight',
            'Use ergonomic furniture'
        ],
        'specialties' => [
            'Orthopedic Surgeon',
            'Physical Therapist',
            'Chiropractor',
            'Pain Management Specialist',
            'Neurologist'
        ]
    ],
    'diabetes' => [
        'title' => 'Diabetes',
        'description' => 'Diabetes is a chronic condition where the body is unable to regulate blood sugar properly.',
        'symptoms' => [
            'Increased thirst and frequent urination',
            'Extreme hunger',
            'Unexplained weight loss',
            'Fatigue and irritability',
            'Blurred vision',
            'Slow-healing sores'
        ],
        'causes' => [
            'Type 1: Autoimmune destruction of insulin-producing cells',
            'Type 2: Insulin resistance and insufficient insulin production',
            'Gestational: Hormonal changes during pregnancy',
            'Lifestyle factors: Poor diet, physical inactivity, obesity'
        ],
        'treatments' => [
            'Regular blood sugar monitoring',
            'Insulin therapy or oral medications',
            'Healthy diet and regular exercise',
            'Weight management',
            'Regular medical check-ups'
        ],
        'prevention' => [
            'Maintain a healthy weight',
            'Regular physical activity',
            'Balanced diet low in processed foods',
            'Regular health screenings',
            'Manage stress levels'
        ],
        'specialties' => [
            'Endocrinologist',
            'Diabetologist',
            'Nutritionist',
            'Cardiologist',
            'Ophthalmologist'
        ]
    ],
    'headache' => [
        'title' => 'Headache',
        'description' => 'Headaches are pain or discomfort in the head, scalp, or neck, which can be primary or secondary.',
        'symptoms' => [
            'Throbbing or constant pain',
            'Pressure or tightness',
            'Sensitivity to light or sound',
            'Nausea or vomiting',
            'Visual disturbances'
        ],
        'causes' => [
            'Stress and tension',
            'Dehydration',
            'Eye strain',
            'Poor posture',
            'Sleep problems'
        ],
        'treatments' => [
            'Over-the-counter pain relievers',
            'Rest in a quiet, dark room',
            'Hydration',
            'Stress management',
            'Prescription medications'
        ],
        'prevention' => [
            'Regular sleep schedule',
            'Stay hydrated',
            'Manage stress',
            'Regular exercise',
            'Maintain good posture'
        ],
        'specialties' => [
            'Neurologist',
            'Primary Care Physician',
            'Pain Management Specialist',
            'Ophthalmologist',
            'Physical Therapist'
        ]
    ],
    'diarrhea' => [
        'title' => 'Diarrhea',
        'description' => 'Diarrhea is loose, watery stools that occur more frequently than usual, often accompanied by abdominal pain.',
        'symptoms' => [
            'Loose, watery stools',
            'Abdominal cramps',
            'Fever',
            'Dehydration',
            'Nausea and vomiting'
        ],
        'causes' => [
            'Viral or bacterial infections',
            'Food intolerance',
            'Medications',
            'Digestive disorders',
            'Contaminated food or water'
        ],
        'treatments' => [
            'Stay hydrated',
            'Over-the-counter medications',
            'BRAT diet (Bananas, Rice, Applesauce, Toast)',
            'Probiotics',
            'Rest'
        ],
        'prevention' => [
            'Wash hands frequently',
            'Safe food handling',
            'Clean water consumption',
            'Proper food storage',
            'Vaccination when available'
        ],
        'specialties' => [
            'Gastroenterologist',
            'Primary Care Physician',
            'Infectious Disease Specialist',
            'Nutritionist',
            'Pediatrician'
        ]
    ],
    'pregnancy' => [
        'title' => 'Pregnancy',
        'description' => 'Pregnancy is the period during which a fetus develops inside a woman\'s uterus, typically lasting about 40 weeks.',
        'symptoms' => [
            'Missed period',
            'Nausea and morning sickness',
            'Breast tenderness',
            'Fatigue',
            'Frequent urination'
        ],
        'causes' => [
            'Fertilization of egg by sperm',
            'Implantation in uterus',
            'Hormonal changes',
            'Natural biological process',
            'Family planning'
        ],
        'treatments' => [
            'Regular prenatal care',
            'Healthy diet',
            'Prenatal vitamins',
            'Exercise as recommended',
            'Adequate rest'
        ],
        'prevention' => [
            'Family planning',
            'Contraception',
            'Regular health check-ups',
            'Healthy lifestyle',
            'Education and awareness'
        ],
        'specialties' => [
            'Obstetrician',
            'Gynecologist',
            'Midwife',
            'Perinatologist',
            'Pediatrician'
        ]
    ],
    'cold' => [
        'title' => 'Cold and Flu',
        'description' => 'The common cold and flu are viral infections that affect the respiratory system, with flu being more severe.',
        'symptoms' => [
            'Runny or stuffy nose',
            'Sore throat',
            'Cough',
            'Fever and chills',
            'Body aches'
        ],
        'causes' => [
            'Viral infection',
            'Contact with infected person',
            'Weakened immune system',
            'Seasonal changes',
            'Poor hygiene'
        ],
        'treatments' => [
            'Rest and hydration',
            'Over-the-counter medications',
            'Warm fluids',
            'Humidifier use',
            'Antiviral medications for flu'
        ],
        'prevention' => [
            'Regular hand washing',
            'Annual flu vaccination',
            'Avoid close contact with sick people',
            'Healthy lifestyle',
            'Adequate sleep'
        ],
        'specialties' => [
            'Primary Care Physician',
            'Infectious Disease Specialist',
            'Pulmonologist',
            'Pediatrician',
            'Family Medicine Physician'
        ]
    ],
    'allergies' => [
        'title' => 'Allergies',
        'description' => 'Allergies occur when the immune system reacts to a foreign substance that doesn\'t cause a reaction in most people.',
        'symptoms' => [
            'Sneezing and runny nose',
            'Itchy eyes and skin',
            'Rash or hives',
            'Swelling',
            'Difficulty breathing'
        ],
        'causes' => [
            'Pollen',
            'Dust mites',
            'Pet dander',
            'Food allergies',
            'Insect stings'
        ],
        'treatments' => [
            'Antihistamines',
            'Decongestants',
            'Nasal sprays',
            'Allergy shots',
            'Avoiding triggers'
        ],
        'prevention' => [
            'Identify and avoid triggers',
            'Keep environment clean',
            'Use air filters',
            'Regular cleaning',
            'Monitor pollen counts'
        ],
        'specialties' => [
            'Allergist',
            'Immunologist',
            'Dermatologist',
            'Primary Care Physician',
            'ENT Specialist'
        ]
    ],
    'kidneystones' => [
        'title' => 'Kidney Stones',
        'description' => 'Kidney stones are hard deposits of minerals and salts that form inside your kidneys.',
        'symptoms' => [
            'Severe pain in side and back',
            'Pain radiating to lower abdomen',
            'Pain during urination',
            'Pink, red or brown urine',
            'Nausea and vomiting'
        ],
        'causes' => [
            'Dehydration',
            'High salt diet',
            'Obesity',
            'Certain medical conditions',
            'Family history'
        ],
        'treatments' => [
            'Pain medication',
            'Drinking water',
            'Medical procedures',
            'Dietary changes',
            'Medications to prevent stones'
        ],
        'prevention' => [
            'Stay hydrated',
            'Reduce salt intake',
            'Maintain healthy weight',
            'Limit animal protein',
            'Regular check-ups'
        ],
        'specialties' => [
            'Urologist',
            'Nephrologist',
            'Primary Care Physician',
            'Dietitian',
            'Pain Management Specialist'
        ]
    ],
    'copd' => [
        'title' => 'COPD',
        'description' => 'Chronic Obstructive Pulmonary Disease (COPD) is a chronic inflammatory lung disease that causes airflow blockage.',
        'symptoms' => [
            'Shortness of breath',
            'Wheezing',
            'Chest tightness',
            'Chronic cough',
            'Frequent respiratory infections'
        ],
        'causes' => [
            'Smoking',
            'Long-term exposure to lung irritants',
            'Genetic factors',
            'Age',
            'Air pollution'
        ],
        'treatments' => [
            'Bronchodilators',
            'Inhaled steroids',
            'Pulmonary rehabilitation',
            'Oxygen therapy',
            'Lifestyle changes'
        ],
        'prevention' => [
            'Quit smoking',
            'Avoid lung irritants',
            'Regular exercise',
            'Vaccinations',
            'Healthy diet'
        ],
        'specialties' => [
            'Pulmonologist',
            'Respiratory Therapist',
            'Primary Care Physician',
            'Thoracic Surgeon',
            'Allergist'
        ]
    ],
    'hypertension' => [
        'title' => 'Hypertension',
        'description' => 'Hypertension, or high blood pressure, is a common condition where the long-term force of blood against artery walls is high enough to cause health problems.',
        'symptoms' => [
            'Usually no symptoms (silent killer)',
            'Headaches in some cases',
            'Shortness of breath',
            'Nosebleeds',
            'Chest pain'
        ],
        'causes' => [
            'Age-related changes',
            'Family history',
            'Obesity',
            'Lack of physical activity',
            'High salt diet'
        ],
        'treatments' => [
            'Lifestyle modifications',
            'Blood pressure medications',
            'Regular monitoring',
            'Stress management',
            'Dietary changes'
        ],
        'prevention' => [
            'Regular exercise',
            'Healthy diet (DASH diet)',
            'Limit alcohol and salt',
            'Maintain healthy weight',
            'Regular check-ups'
        ],
        'specialties' => [
            'Cardiologist',
            'Primary Care Physician',
            'Nephrologist',
            'Nutritionist',
            'Exercise Physiologist'
        ]
    ],
    'asthma' => [
        'title' => 'Asthma',
        'description' => 'Asthma is a condition in which your airways narrow and swell and may produce extra mucus, making breathing difficult.',
        'symptoms' => [
            'Shortness of breath',
            'Chest tightness or pain',
            'Wheezing when exhaling',
            'Trouble sleeping due to breathing problems',
            'Coughing or wheezing attacks'
        ],
        'causes' => [
            'Genetic factors',
            'Environmental triggers',
            'Respiratory infections',
            'Allergies',
            'Air pollution'
        ],
        'treatments' => [
            'Inhalers (rescue and controller)',
            'Avoiding triggers',
            'Action plan for attacks',
            'Regular monitoring',
            'Medication management'
        ],
        'prevention' => [
            'Identify and avoid triggers',
            'Regular medication use',
            'Vaccinations',
            'Regular check-ups',
            'Healthy lifestyle'
        ],
        'specialties' => [
            'Pulmonologist',
            'Allergist',
            'Primary Care Physician',
            'Respiratory Therapist',
            'Immunologist'
        ]
    ],
    'adhd' => [
        'title' => 'ADHD',
        'description' => 'Attention-Deficit/Hyperactivity Disorder (ADHD) is a neurodevelopmental disorder characterized by inattention, hyperactivity, and impulsivity.',
        'symptoms' => [
            'Difficulty focusing and sustaining attention',
            'Hyperactivity and restlessness',
            'Impulsive behavior',
            'Poor time management',
            'Difficulty organizing tasks'
        ],
        'causes' => [
            'Genetic factors',
            'Brain structure and function differences',
            'Environmental factors',
            'Premature birth',
            'Brain injury'
        ],
        'treatments' => [
            'Behavioral therapy',
            'Medication management',
            'Educational support',
            'Parent training',
            'Lifestyle modifications'
        ],
        'prevention' => [
            'Early diagnosis and intervention',
            'Healthy lifestyle habits',
            'Regular exercise',
            'Adequate sleep',
            'Stress management'
        ],
        'specialties' => [
            'Psychiatrist',
            'Psychologist',
            'Neurologist',
            'Pediatrician',
            'Behavioral Therapist'
        ]
    ],
    'anxiety' => [
        'title' => 'Anxiety',
        'description' => 'Anxiety is a mental health condition characterized by excessive worry, fear, and nervousness that can interfere with daily life.',
        'symptoms' => [
            'Excessive worry',
            'Restlessness',
            'Difficulty concentrating',
            'Sleep problems',
            'Physical symptoms (racing heart, sweating)'
        ],
        'causes' => [
            'Genetic factors',
            'Brain chemistry',
            'Environmental stress',
            'Trauma',
            'Medical conditions'
        ],
        'treatments' => [
            'Psychotherapy',
            'Medication',
            'Lifestyle changes',
            'Stress management',
            'Support groups'
        ],
        'prevention' => [
            'Regular exercise',
            'Healthy sleep habits',
            'Stress management',
            'Social support',
            'Avoiding alcohol and drugs'
        ],
        'specialties' => [
            'Psychiatrist',
            'Psychologist',
            'Therapist',
            'Primary Care Physician',
            'Mental Health Counselor'
        ]
    ],
    'depression' => [
        'title' => 'Depression',
        'description' => 'Depression is a mood disorder that causes persistent feelings of sadness and loss of interest in activities.',
        'symptoms' => [
            'Persistent sadness',
            'Loss of interest in activities',
            'Changes in sleep patterns',
            'Changes in appetite',
            'Feelings of worthlessness'
        ],
        'causes' => [
            'Brain chemistry',
            'Hormonal changes',
            'Genetic factors',
            'Life events',
            'Medical conditions'
        ],
        'treatments' => [
            'Psychotherapy',
            'Antidepressant medications',
            'Lifestyle changes',
            'Support groups',
            'Exercise'
        ],
        'prevention' => [
            'Regular exercise',
            'Healthy sleep habits',
            'Social support',
            'Stress management',
            'Early intervention'
        ],
        'specialties' => [
            'Psychiatrist',
            'Psychologist',
            'Therapist',
            'Primary Care Physician',
            'Mental Health Counselor'
        ]
    ],
    'migraine' => [
        'title' => 'Migraine',
        'description' => 'Migraine is a neurological condition characterized by severe, recurring headaches often accompanied by other symptoms.',
        'symptoms' => [
            'Severe headache',
            'Nausea and vomiting',
            'Sensitivity to light and sound',
            'Visual disturbances',
            'Dizziness'
        ],
        'causes' => [
            'Genetic factors',
            'Environmental triggers',
            'Hormonal changes',
            'Stress',
            'Certain foods and drinks'
        ],
        'treatments' => [
            'Pain medications',
            'Preventive medications',
            'Lifestyle changes',
            'Stress management',
            'Alternative therapies'
        ],
        'prevention' => [
            'Identify and avoid triggers',
            'Regular sleep schedule',
            'Stress management',
            'Regular exercise',
            'Healthy diet'
        ],
        'specialties' => [
            'Neurologist',
            'Headache Specialist',
            'Primary Care Physician',
            'Pain Management Specialist',
            'Ophthalmologist'
        ]
    ],
    'breastcancer' => [
        'title' => 'Breast Cancer',
        'description' => 'Breast cancer is a type of cancer that forms in the cells of the breasts, typically in the milk ducts or lobules.',
        'symptoms' => [
            'Lump in breast or underarm',
            'Changes in breast size or shape',
            'Nipple discharge or inversion',
            'Skin changes on breast',
            'Breast pain or tenderness'
        ],
        'causes' => [
            'Genetic mutations',
            'Family history',
            'Age and gender',
            'Hormonal factors',
            'Lifestyle factors'
        ],
        'treatments' => [
            'Surgery',
            'Radiation therapy',
            'Chemotherapy',
            'Hormone therapy',
            'Targeted therapy'
        ],
        'prevention' => [
            'Regular screenings',
            'Healthy lifestyle',
            'Limit alcohol',
            'Maintain healthy weight',
            'Breastfeeding when possible'
        ],
        'specialties' => [
            'Oncologist',
            'Breast Surgeon',
            'Radiation Oncologist',
            'Medical Oncologist',
            'Plastic Surgeon'
        ]
    ],
    'hivaids' => [
        'title' => 'HIV/AIDS',
        'description' => 'HIV (Human Immunodeficiency Virus) is a virus that attacks the immune system, and AIDS (Acquired Immunodeficiency Syndrome) is the final stage of HIV infection.',
        'symptoms' => [
            'Flu-like symptoms',
            'Fever and chills',
            'Night sweats',
            'Muscle aches',
            'Fatigue'
        ],
        'causes' => [
            'Unprotected sexual contact',
            'Sharing needles',
            'Mother-to-child transmission',
            'Blood transfusions',
            'Occupational exposure'
        ],
        'treatments' => [
            'Antiretroviral therapy (ART)',
            'Regular medical care',
            'Healthy lifestyle',
            'Preventive medications',
            'Support services'
        ],
        'prevention' => [
            'Safe sex practices',
            'Regular testing',
            'Needle exchange programs',
            'Pre-exposure prophylaxis',
            'Education and awareness'
        ],
        'specialties' => [
            'Infectious Disease Specialist',
            'HIV Specialist',
            'Primary Care Physician',
            'Immunologist',
            'Social Worker'
        ]
    ],
    'obesity' => [
        'title' => 'Obesity',
        'description' => 'Obesity is a complex disease involving an excessive amount of body fat that increases the risk of other health problems.',
        'symptoms' => [
            'Excess body fat',
            'Difficulty with physical activity',
            'Shortness of breath',
            'Joint pain',
            'Sleep problems'
        ],
        'causes' => [
            'Unhealthy diet',
            'Lack of physical activity',
            'Genetic factors',
            'Medical conditions',
            'Environmental factors'
        ],
        'treatments' => [
            'Dietary changes',
            'Regular exercise',
            'Behavioral therapy',
            'Weight loss medications',
            'Bariatric surgery'
        ],
        'prevention' => [
            'Healthy eating habits',
            'Regular physical activity',
            'Portion control',
            'Stress management',
            'Adequate sleep'
        ],
        'specialties' => [
            'Bariatrician',
            'Nutritionist',
            'Endocrinologist',
            'Primary Care Physician',
            'Exercise Physiologist'
        ]
    ],
    'gerd' => [
        'title' => 'GERD',
        'description' => 'Gastroesophageal Reflux Disease (GERD) is a chronic digestive disorder where stomach acid frequently flows back into the esophagus.',
        'symptoms' => [
            'Heartburn',
            'Regurgitation',
            'Chest pain',
            'Difficulty swallowing',
            'Chronic cough'
        ],
        'causes' => [
            'Weak lower esophageal sphincter',
            'Hiatal hernia',
            'Obesity',
            'Pregnancy',
            'Certain medications'
        ],
        'treatments' => [
            'Lifestyle modifications',
            'Over-the-counter medications',
            'Prescription medications',
            'Surgery',
            'Dietary changes'
        ],
        'prevention' => [
            'Maintain healthy weight',
            'Avoid trigger foods',
            'Eat smaller meals',
            'Don\'t lie down after eating',
            'Quit smoking'
        ],
        'specialties' => [
            'Gastroenterologist',
            'Primary Care Physician',
            'Surgeon',
            'Nutritionist',
            'Dietitian'
        ]
    ],
    'thyroiddisorders' => [
        'title' => 'Thyroid Disorders',
        'description' => 'Thyroid disorders are conditions that affect the thyroid gland, which regulates metabolism and other vital body functions.',
        'symptoms' => [
            'Weight changes',
            'Fatigue',
            'Mood changes',
            'Temperature sensitivity',
            'Hair and skin changes'
        ],
        'causes' => [
            'Autoimmune disorders',
            'Iodine deficiency',
            'Genetic factors',
            'Radiation exposure',
            'Certain medications'
        ],
        'treatments' => [
            'Hormone replacement therapy',
            'Anti-thyroid medications',
            'Radioactive iodine',
            'Surgery',
            'Lifestyle modifications'
        ],
        'prevention' => [
            'Regular check-ups',
            'Adequate iodine intake',
            'Healthy lifestyle',
            'Stress management',
            'Early detection'
        ],
        'specialties' => [
            'Endocrinologist',
            'Primary Care Physician',
            'Nuclear Medicine Specialist',
            'Surgeon',
            'Nutritionist'
        ]
    ],
    'stroke' => [
        'title' => 'Stroke',
        'description' => 'A stroke occurs when blood supply to part of the brain is interrupted or reduced, preventing brain tissue from getting oxygen and nutrients.',
        'symptoms' => [
            'Sudden numbness or weakness',
            'Confusion or trouble speaking',
            'Vision problems',
            'Difficulty walking',
            'Severe headache'
        ],
        'causes' => [
            'High blood pressure',
            'Atherosclerosis',
            'Heart disease',
            'Diabetes',
            'Smoking'
        ],
        'treatments' => [
            'Emergency medical care',
            'Clot-busting drugs',
            'Surgery',
            'Rehabilitation',
            'Medications'
        ],
        'prevention' => [
            'Control blood pressure',
            'Manage diabetes',
            'Quit smoking',
            'Regular exercise',
            'Healthy diet'
        ],
        'specialties' => [
            'Neurologist',
            'Neurosurgeon',
            'Rehabilitation Specialist',
            'Cardiologist',
            'Primary Care Physician'
        ]
    ],
    'skinallergies' => [
        'title' => 'Skin Allergies',
        'description' => 'Skin allergies are immune system reactions that cause skin inflammation, itching, and other symptoms when exposed to certain substances.',
        'symptoms' => [
            'Itching',
            'Redness',
            'Rashes',
            'Hives',
            'Swelling'
        ],
        'causes' => [
            'Contact with allergens',
            'Food allergies',
            'Medications',
            'Insect bites',
            'Environmental factors'
        ],
        'treatments' => [
            'Antihistamines',
            'Topical corticosteroids',
            'Avoiding triggers',
            'Moisturizers',
            'Immunotherapy'
        ],
        'prevention' => [
            'Identify triggers',
            'Use hypoallergenic products',
            'Protect skin',
            'Maintain skin barrier',
            'Regular moisturizing'
        ],
        'specialties' => [
            'Dermatologist',
            'Allergist',
            'Immunologist',
            'Primary Care Physician',
            'Pediatrician'
        ]
    ],
    'ulcerativecolitis' => [
        'title' => 'Ulcerative Colitis',
        'description' => 'Ulcerative colitis is an inflammatory bowel disease that causes long-lasting inflammation and ulcers in the digestive tract.',
        'symptoms' => [
            'Diarrhea with blood',
            'Abdominal pain',
            'Rectal pain',
            'Weight loss',
            'Fatigue'
        ],
        'causes' => [
            'Immune system malfunction',
            'Genetic factors',
            'Environmental factors',
            'Gut bacteria',
            'Stress'
        ],
        'treatments' => [
            'Anti-inflammatory drugs',
            'Immune system suppressors',
            'Biologics',
            'Surgery',
            'Lifestyle changes'
        ],
        'prevention' => [
            'Stress management',
            'Healthy diet',
            'Regular exercise',
            'Avoiding triggers',
            'Regular check-ups'
        ],
        'specialties' => [
            'Gastroenterologist',
            'Colorectal Surgeon',
            'Nutritionist',
            'Primary Care Physician',
            'Rheumatologist'
        ]
    ],
    'crohnsdisease' => [
        'title' => 'Crohn\'s Disease',
        'description' => 'Crohn\'s disease is a type of inflammatory bowel disease that can affect any part of the digestive tract.',
        'symptoms' => [
            'Abdominal pain',
            'Diarrhea',
            'Weight loss',
            'Fatigue',
            'Mouth sores'
        ],
        'causes' => [
            'Immune system issues',
            'Genetics',
            'Environmental factors',
            'Gut bacteria',
            'Smoking'
        ],
        'treatments' => [
            'Anti-inflammatory drugs',
            'Immune system suppressors',
            'Biologics',
            'Surgery',
            'Nutritional therapy'
        ],
        'prevention' => [
            'Quit smoking',
            'Stress management',
            'Healthy diet',
            'Regular exercise',
            'Regular check-ups'
        ],
        'specialties' => [
            'Gastroenterologist',
            'Colorectal Surgeon',
            'Nutritionist',
            'Primary Care Physician',
            'Rheumatologist'
        ]
    ],
    'irritablebowelsyndrome' => [
        'title' => 'Irritable Bowel Syndrome',
        'description' => 'IBS is a common disorder affecting the large intestine, causing cramping, abdominal pain, bloating, gas, and diarrhea or constipation.',
        'symptoms' => [
            'Abdominal pain',
            'Bloating',
            'Gas',
            'Diarrhea or constipation',
            'Mucus in stool'
        ],
        'causes' => [
            'Muscle contractions in intestine',
            'Nervous system abnormalities',
            'Inflammation in intestines',
            'Bacterial changes',
            'Food triggers'
        ],
        'treatments' => [
            'Dietary changes',
            'Stress management',
            'Medications',
            'Probiotics',
            'Lifestyle modifications'
        ],
        'prevention' => [
            'Identify food triggers',
            'Stress management',
            'Regular exercise',
            'Adequate sleep',
            'Fiber management'
        ],
        'specialties' => [
            'Gastroenterologist',
            'Primary Care Physician',
            'Nutritionist',
            'Dietitian',
            'Mental Health Professional'
        ]
    ],
    'coldflu' => [
        'title' => 'Cold and Flu',
        'description' => 'The common cold and influenza are respiratory illnesses caused by different viruses. While both share similar symptoms, the flu tends to be more severe and can lead to serious complications. Both conditions are highly contagious and spread through respiratory droplets.',
        'symptoms' => [
            'Runny or stuffy nose',
            'Sore throat',
            'Cough',
            'Sneezing',
            'Fever (more common with flu)',
            'Body aches',
            'Fatigue'
        ],
        'causes' => [
            'Rhinoviruses (common cold)',
            'Influenza viruses (flu)',
            'Transmission through respiratory droplets',
            'Contact with contaminated surfaces'
        ],
        'treatments' => [
            'Rest',
            'Hydration',
            'Over-the-counter cold and flu medications',
            'Antiviral drugs (for flu, if prescribed early)',
            'Humidifiers to ease congestion'
        ],
        'prevention' => [
            'Frequent handwashing',
            'Avoiding close contact with sick individuals',
            'Annual flu vaccination',
            'Covering mouth and nose when sneezing or coughing',
            'Disinfecting commonly touched surfaces'
        ],
        'specialties' => [
            'Primary Care Physician',
            'Infectious Disease Specialist',
            'Pulmonologist'
        ]
    ],
    'gallstones' => [
        'title' => 'Gallstones',
        'description' => 'Gallstones are hardened deposits of digestive fluid that can form in the gallbladder. They can vary in size and may cause pain or blockages in the bile ducts. While some individuals remain asymptomatic, others may require treatment.',
        'symptoms' => [
            'Sudden and intense pain in the upper right abdomen',
            'Back pain between shoulder blades',
            'Nausea or vomiting',
            'Indigestion',
            'Jaundice (yellowing of skin and eyes)'
        ],
        'causes' => [
            'Excess cholesterol in bile',
            'High bilirubin levels',
            'Incomplete emptying of the gallbladder',
            'Obesity',
            'Diet high in fat and cholesterol'
        ],
        'treatments' => [
            'Surgical removal of the gallbladder (cholecystectomy)',
            'Medications to dissolve gallstones',
            'Endoscopic procedures to remove stones',
            'Dietary modifications'
        ],
        'prevention' => [
            'Maintaining a healthy weight',
            'Eating a balanced diet rich in fiber',
            'Regular physical activity',
            'Avoiding rapid weight loss'
        ],
        'specialties' => [
            'Gastroenterologist',
            'General Surgeon',
            'Primary Care Physician'
        ]
    ],
    'pancreatitis' => [
        'title' => 'Pancreatitis',
        'description' => 'Pancreatitis is the inflammation of the pancreas, which can occur suddenly (acute) or over many years (chronic). It affects the pancreas\' ability to aid digestion and regulate blood sugar.',
        'symptoms' => [
            'Upper abdominal pain that may radiate to the back',
            'Nausea and vomiting',
            'Fever',
            'Rapid pulse',
            'Swollen and tender abdomen'
        ],
        'causes' => [
            'Gallstones',
            'Chronic and excessive alcohol consumption',
            'Certain medications',
            'High triglyceride levels',
            'Abdominal injury'
        ],
        'treatments' => [
            'Hospitalization for intravenous fluids and pain management',
            'Fasting to rest the pancreas',
            'Treatment of underlying causes (e.g., gallstone removal)',
            'Enzyme supplements for digestion',
            'Lifestyle changes, including abstaining from alcohol'
        ],
        'prevention' => [
            'Limiting alcohol intake',
            'Maintaining a healthy diet',
            'Regular exercise',
            'Monitoring and managing triglyceride levels',
            'Avoiding smoking'
        ],
        'specialties' => [
            'Gastroenterologist',
            'Endocrinologist',
            'Primary Care Physician'
        ]
    ],
    'celiacdisease' => [
        'title' => 'Celiac Disease',
        'description' => 'Celiac disease is an autoimmune disorder where ingestion of gluten leads to damage in the small intestine. It affects nutrient absorption and can cause various gastrointestinal and systemic symptoms.',
        'symptoms' => [
            'Diarrhea',
            'Fatigue',
            'Weight loss',
            'Bloating and gas',
            'Abdominal pain',
            'Nausea and vomiting',
            'Constipation'
        ],
        'causes' => [
            'Genetic predisposition',
            'Consumption of gluten-containing foods',
            'Immune system response damaging the small intestine lining'
        ],
        'treatments' => [
            'Strict lifelong gluten-free diet',
            'Nutritional supplements to address deficiencies',
            'Regular monitoring for associated conditions',
            'Consultation with a dietitian'
        ],
        'prevention' => [
            'Currently, there is no known prevention for celiac disease, but early diagnosis and treatment can prevent complications'
        ],
        'specialties' => [
            'Gastroenterologist',
            'Dietitian',
            'Primary Care Physician'
        ]
    ],
    'gastritis' => [
        'title' => 'Gastritis',
        'description' => 'Gastritis is the inflammation of the stomach lining, which can be acute or chronic. It can result from various factors, including infections, certain medications, and excessive alcohol consumption.',
        'symptoms' => [
            'Upper abdominal pain or discomfort',
            'Nausea',
            'Vomiting',
            'Bloating',
            'Loss of appetite',
            'Indigestion'
        ],
        'causes' => [
            'Helicobacter pylori infection',
            'Regular use of nonsteroidal anti-inflammatory drugs (NSAIDs)',
            'Excessive alcohol consumption',
            'Stress',
            'Autoimmune disorders'
        ],
        'treatments' => [
            'Medications to reduce stomach acid',
            'Antibiotics to treat H. pylori infection',
            'Avoiding irritants like NSAIDs and alcohol',
            'Dietary modifications'
        ],
        'prevention' => [
            'Limiting alcohol intake',
            'Avoiding NSAIDs when possible',
            'Managing stress',
            'Eating a balanced diet',
            'Regular medical check-ups'
        ],
        'specialties' => [
            'Gastroenterologist',
            'Primary Care Physician',
            'Dietitian'
        ]
    ],
    'hemorrhoids' => [
        'title' => 'Hemorrhoids',
        'description' => 'Hemorrhoids are swollen veins in the lower rectum and anus, similar to varicose veins. They can develop inside the rectum (internal hemorrhoids) or under the skin around the anus (external hemorrhoids).',
        'symptoms' => [
            'Painless bleeding during bowel movements',
            'Itching or irritation in the anal region',
            'Pain or discomfort',
            'Swelling around the anus',
            'A lump near the anus, which may be sensitive or painful'
        ],
        'causes' => [
            'Straining during bowel movements',
            'Sitting for long periods on the toilet',
            'Chronic constipation or diarrhea',
            'Obesity',
            'Pregnancy'
        ],
        'treatments' => [
            'Over-the-counter creams and ointments',
            'Warm sitz baths',
            'High-fiber diet and increased fluid intake',
            'Minimally invasive procedures (e.g., rubber band ligation)',
            'Surgical removal in severe cases'
        ],
        'prevention' => [
            'Eating a high-fiber diet',
            'Drinking plenty of fluids',
            'Exercising regularly',
            'Avoiding prolonged sitting',
            'Responding promptly to the urge to have a bowel movement'
        ],
        'specialties' => [
            'Gastroenterologist',
            'Colorectal Surgeon',
            'Primary Care Physician'
        ]
    ],
    'bronchitis' => [
    'title' => 'Bronchitis',
    'description' => 'Bronchitis is an inflammation of the lining of bronchial tubes, which carry air to and from the lungs. It can be acute or chronic and is often caused by viruses or irritants like smoke.',
    'symptoms' => [
        'Cough',
        'Production of mucus',
        'Fatigue',
        'Shortness of breath',
        'Slight fever and chills',
        'Chest discomfort'
    ],
    'causes' => [
        'Viral infections',
        'Bacterial infections',
        'Tobacco smoke',
        'Air pollution',
        'Dust and toxic gases'
    ],
    'treatments' => [
        'Rest and fluids',
        'Cough suppressants',
        'Bronchodilators',
        'Anti-inflammatory medications',
        'Avoiding irritants'
    ],
    'prevention' => [
        'Avoid smoking',
        'Get vaccinated',
        'Wash hands frequently',
        'Wear a mask in polluted areas',
        'Boost immune health'
    ],
    'specialties' => [
        'Pulmonologist',
        'Primary Care Physician',
        'Allergist/Immunologist',
        'Internal Medicine Specialist'
    ]
],
'pneumonia' => [
    'title' => 'Pneumonia',
    'description' => 'Pneumonia is an infection that inflames the air sacs in one or both lungs. It may fill the lungs with fluid or pus, causing cough, fever, and difficulty breathing.',
    'symptoms' => [
        'Cough with phlegm or pus',
        'Fever',
        'Chills',
        'Shortness of breath',
        'Chest pain when breathing or coughing',
        'Fatigue'
    ],
    'causes' => [
        'Bacteria',
        'Viruses',
        'Fungi',
        'Aspiration of food or liquid',
        'Hospital-acquired infections'
    ],
    'treatments' => [
        'Antibiotics',
        'Antiviral or antifungal medication',
        'Hospital care (in severe cases)',
        'Oxygen therapy',
        'Rest and fluids'
    ],
    'prevention' => [
        'Vaccination',
        'Good hygiene',
        'Healthy lifestyle',
        'Avoid smoking',
        'Manage chronic illnesses'
    ],
    'specialties' => [
        'Pulmonologist',
        'Infectious Disease Specialist',
        'Primary Care Physician',
        'Respiratory Therapist'
    ]
],
'sinusitis' => [
    'title' => 'Sinusitis',
    'description' => 'Sinusitis is the inflammation or swelling of the tissue lining the sinuses, often due to infection, allergies, or nasal polyps.',
    'symptoms' => [
        'Facial pain or pressure',
        'Nasal congestion',
        'Loss of smell',
        'Cough or congestion',
        'Fever',
        'Headache'
    ],
    'causes' => [
        'Viral infections',
        'Bacterial infections',
        'Allergies',
        'Nasal polyps',
        'Deviated nasal septum'
    ],
    'treatments' => [
        'Nasal decongestant sprays',
        'Saline nasal irrigation',
        'Antibiotics (if bacterial)',
        'Corticosteroids',
        'Surgery (for chronic cases)'
    ],
    'prevention' => [
        'Avoid allergens',
        'Use a humidifier',
        'Stay hydrated',
        'Manage allergies',
        'Practice good hygiene'
    ],
    'specialties' => [
        'Otolaryngologist (ENT)',
        'Allergist',
        'Primary Care Physician'
    ]
],
'sleepapnea' => [
    'title' => 'Sleep Apnea',
    'description' => 'Sleep apnea is a serious sleep disorder in which breathing repeatedly stops and starts, commonly due to throat muscles intermittently relaxing and blocking the airway.',
    'symptoms' => [
        'Loud snoring',
        'Episodes of stopped breathing',
        'Gasping for air during sleep',
        'Morning headache',
        'Excessive daytime sleepiness',
        'Irritability'
    ],
    'causes' => [
        'Obstructed airway',
        'Obesity',
        'Narrowed airway',
        'Family history',
        'Use of alcohol or sedatives'
    ],
    'treatments' => [
        'CPAP (Continuous Positive Airway Pressure)',
        'Lifestyle changes',
        'Oral appliances',
        'Surgery',
        'Weight loss'
    ],
    'prevention' => [
        'Maintain a healthy weight',
        'Avoid alcohol before bed',
        'Quit smoking',
        'Sleep on your side',
        'Regular exercise'
    ],
    'specialties' => [
        'Sleep Specialist',
        'Pulmonologist',
        'Otolaryngologist (ENT)',
        'Dentist (for oral appliances)'
    ]
],
'tuberculosis' => [
    'title' => 'Tuberculosis',
    'description' => 'Tuberculosis (TB) is a potentially serious infectious disease that mainly affects the lungs and is caused by the bacterium Mycobacterium tuberculosis.',
    'symptoms' => [
        'Persistent cough',
        'Coughing up blood',
        'Chest pain',
        'Fatigue',
        'Weight loss',
        'Fever and night sweats'
    ],
    'causes' => [
        'Mycobacterium tuberculosis bacteria',
        'Spread through airborne droplets from coughs/sneezes'
    ],
    'treatments' => [
        'Antibiotic treatment (6-9 months)',
        'Directly observed therapy (DOT)',
        'Hospitalization (in severe cases)',
        'Supportive care'
    ],
    'prevention' => [
        'BCG vaccine',
        'Screening for high-risk groups',
        'Proper ventilation',
        'Wearing masks in high-risk areas',
        'Complete prescribed medication'
    ],
    'specialties' => [
        'Infectious Disease Specialist',
        'Pulmonologist',
        'Public Health Specialist'
    ]
],
'pulmonaryembolism' => [
    'title' => 'Pulmonary Embolism',
    'description' => 'Pulmonary embolism is a blockage in one of the pulmonary arteries in the lungs, usually caused by blood clots that travel from the legs or other parts of the body.',
    'symptoms' => [
        'Sudden shortness of breath',
        'Chest pain that worsens with breathing',
        'Rapid heart rate',
        'Cough (may produce bloody sputum)',
        'Dizziness or fainting'
    ],
    'causes' => [
        'Deep vein thrombosis (DVT)',
        'Prolonged immobility',
        'Surgery',
        'Cancer',
        'Smoking and obesity'
    ],
    'treatments' => [
        'Anticoagulants (blood thinners)',
        'Thrombolytics (clot dissolvers)',
        'Surgical removal of clot',
        'Inferior vena cava filter',
        'Oxygen therapy'
    ],
    'prevention' => [
        'Stay active',
        'Use compression stockings',
        'Take blood thinners if at risk',
        'Avoid prolonged sitting',
        'Maintain a healthy weight'
    ],
    'specialties' => [
        'Pulmonologist',
        'Cardiologist',
        'Hematologist',
        'Emergency Medicine Specialist'
    ]
],
'lungcancer' => [
    'title' => 'Lung Cancer',
    'description' => 'Lung cancer is a type of cancer that begins in the lungs, often linked to smoking, but can also affect non-smokers.',
    'symptoms' => [
        'Persistent cough',
        'Coughing up blood',
        'Chest pain',
        'Shortness of breath',
        'Unexplained weight loss',
        'Hoarseness'
    ],
    'causes' => [
        'Smoking',
        'Secondhand smoke',
        'Exposure to radon gas',
        'Asbestos exposure',
        'Genetic mutations'
    ],
    'treatments' => [
        'Surgery',
        'Chemotherapy',
        'Radiation therapy',
        'Targeted drug therapy',
        'Immunotherapy'
    ],
    'prevention' => [
        'Avoid smoking',
        'Test home for radon',
        'Use protective equipment around toxins',
        'Healthy diet and exercise',
        'Regular screening (if at risk)'
    ],
    'specialties' => [
        'Oncologist',
        'Pulmonologist',
        'Thoracic Surgeon',
        'Radiation Oncologist'
    ]
]
,
    'pleurisy' => [
    'title' => 'Pleurisy',
    'description' => 'Pleurisy is inflammation of the pleura, the double-layered membrane surrounding the lungs and lining the chest cavity. This condition causes sharp chest pain (pleuritic pain) that worsens during breathing.',
    'symptoms' => [
        'Sharp chest pain when breathing',
        'Shortness of breath',
        'Cough',
        'Fever (in some cases)',
        'Pain in the shoulder or back'
    ],
    'causes' => [
        'Viral infections',
        'Bacterial infections (e.g., pneumonia)',
        'Tuberculosis',
        'Autoimmune conditions (e.g., lupus, rheumatoid arthritis)',
        'Pulmonary embolism',
        'Lung cancer'
    ],
    'treatments' => [
        'Treatment of the underlying condition',
        'Nonsteroidal anti-inflammatory drugs (NSAIDs)',
        'Antibiotics (if bacterial infection)',
        'Corticosteroids (if autoimmune-related)',
        'Drainage of pleural fluid (if present)'
    ],
    'prevention' => [
        'Avoid respiratory infections',
        'Manage chronic conditions',
        'Get vaccinated (e.g., influenza, pneumococcal)',
        'Do not smoke',
        'Seek early treatment for chest pain'
    ],
    'specialties' => [
        'Pulmonologist',
        'Internal Medicine',
        'Infectious Disease Specialist'
    ]
],
'heartdisease' => [
    'title' => 'Heart Disease',
    'description' => 'Heart disease refers to a range of conditions that affect the heart, including coronary artery disease, arrhythmias, and congenital heart defects. It is one of the leading causes of death globally.',
    'symptoms' => [
        'Chest pain or discomfort (angina)',
        'Shortness of breath',
        'Fatigue',
        'Irregular heartbeat',
        'Swelling in legs, ankles, or feet',
        'Fainting'
    ],
    'causes' => [
        'High blood pressure',
        'High cholesterol',
        'Smoking',
        'Diabetes',
        'Obesity',
        'Family history of heart disease'
    ],
    'treatments' => [
        'Lifestyle changes (diet, exercise)',
        'Medications (e.g., beta-blockers, statins)',
        'Surgery (e.g., bypass, valve repair)',
        'Cardiac catheterization',
        'Implantable devices (e.g., pacemaker, defibrillator)'
    ],
    'prevention' => [
        'Healthy diet',
        'Regular physical activity',
        'Avoid smoking',
        'Control blood pressure and cholesterol',
        'Manage stress',
        'Regular health screenings'
    ],
    'specialties' => [
        'Cardiologist',
        'Primary Care Physician'
    ]
],
'arrhythmia' => [
    'title' => 'Arrhythmia',
    'description' => 'Arrhythmia refers to an abnormal heart rhythm, which can be too fast (tachycardia), too slow (bradycardia), or irregular. It may be harmless or life-threatening depending on the type and severity.',
    'symptoms' => [
        'Palpitations (fluttering in chest)',
        'Dizziness or lightheadedness',
        'Fainting',
        'Shortness of breath',
        'Chest discomfort',
        'Fatigue'
    ],
    'causes' => [
        'Coronary artery disease',
        'Electrolyte imbalances',
        'Heart surgery',
        'High blood pressure',
        'Congenital heart defects',
        'Thyroid problems'
    ],
    'treatments' => [
        'Medications (antiarrhythmics, beta-blockers)',
        'Lifestyle changes',
        'Pacemaker or implantable defibrillator',
        'Catheter ablation',
        'Surgery (in rare cases)'
    ],
    'prevention' => [
        'Avoid caffeine and alcohol (if triggers)',
        'Regular exercise',
        'Manage underlying conditions',
        'Quit smoking',
        'Monitor heart health regularly'
    ],
    'specialties' => [
        'Cardiologist',
        'Electrophysiologist'
    ]
],
'heartfailure' => [
    'title' => 'Heart Failure',
    'description' => 'Heart failure occurs when the heart cant pump enough blood to meet the bodys needs. It can affect the left side, right side, or both sides of the heart, leading to fatigue and fluid buildup.',
    'symptoms' => [
        'Shortness of breath',
        'Fatigue and weakness',
        'Swelling in legs, ankles, and feet',
        'Rapid or irregular heartbeat',
        'Persistent cough or wheezing',
        'Increased need to urinate at night'
    ],
    'causes' => [
        'Coronary artery disease',
        'High blood pressure',
        'Cardiomyopathy',
        'Heart valve disease',
        'Myocardial infarction (heart attack)',
        'Arrhythmias'
    ],
    'treatments' => [
        'Medications (e.g., ACE inhibitors, diuretics, beta-blockers)',
        'Lifestyle changes (low-sodium diet, exercise)',
        'Implantable devices (e.g., pacemakers)',
        'Surgical procedures (e.g., valve repair, bypass)',
        'Heart transplant (in severe cases)'
    ],
    'prevention' => [
        'Control blood pressure and diabetes',
        'Dont smoke',
        'Exercise regularly',
        'Limit alcohol',
        'Eat a heart-healthy diet'
    ],
    'specialties' => [
        'Cardiologist',
        'Heart Failure Specialist'
    ]
],
'atherosclerosis' => [
    'title' => 'Atherosclerosis',
    'description' => 'Atherosclerosis is the buildup of fats, cholesterol, and other substances in and on artery walls (plaque), which can restrict blood flow. This condition can lead to heart attacks, strokes, and other cardiovascular issues.',
    'symptoms' => [
        'Chest pain or angina',
        'Leg pain when walking (claudication)',
        'Numbness or weakness in limbs',
        'High blood pressure',
        'Kidney failure (if arteries to kidneys are affected)'
    ],
    'causes' => [
        'High cholesterol',
        'High blood pressure',
        'Smoking',
        'Diabetes',
        'Obesity',
        'Inflammation from chronic diseases'
    ],
    'treatments' => [
        'Cholesterol-lowering medications (statins)',
        'Antiplatelet medications (aspirin)',
        'Lifestyle changes',
        'Angioplasty and stent placement',
        'Bypass surgery'
    ],
    'prevention' => [
        'Healthy diet',
        'Quit smoking',
        'Control blood pressure and cholesterol',
        'Regular physical activity',
        'Manage stress'
    ],
    'specialties' => [
        'Cardiologist',
        'Vascular Surgeon'
    ]
],
'peripheralarterydisease' => [
    'title' => 'Peripheral Artery Disease',
    'description' => 'Peripheral artery disease (PAD) is a circulatory condition where narrowed arteries reduce blood flow to the limbs, usually the legs. Its a common sign of atherosclerosis.',
    'symptoms' => [
        'Leg pain while walking (claudication)',
        'Numbness or weakness in legs',
        'Coldness in lower leg or foot',
        'Sores on toes, feet, or legs that wont heal',
        'Shiny skin on legs',
        'Weak pulse in legs or feet'
    ],
    'causes' => [
        'Atherosclerosis',
        'Smoking',
        'Diabetes',
        'High cholesterol',
        'High blood pressure',
        'Obesity'
    ],
    'treatments' => [
        'Lifestyle modifications (exercise, quit smoking)',
        'Cholesterol and blood pressure medications',
        'Antiplatelet drugs',
        'Angioplasty and stenting',
        'Bypass surgery'
    ],
    'prevention' => [
        'Dont smoke',
        'Exercise regularly',
        'Control diabetes and blood pressure',
        'Eat a healthy diet',
        'Manage cholesterol levels'
    ],
    'specialties' => [
        'Vascular Surgeon',
        'Cardiologist'
    ]
],
'deepveinthrombosis' => [
    'title' => 'Deep Vein Thrombosis',
    'description' => 'Deep vein thrombosis (DVT) occurs when a blood clot forms in a deep vein, usually in the legs. If the clot dislodges and travels to the lungs, it can cause a life-threatening pulmonary embolism.',
    'symptoms' => [
        'Swelling in the affected leg',
        'Pain or tenderness in the leg',
        'Red or discolored skin',
        'Warmth in the leg',
        'Leg cramps, usually starting in the calf'
    ],
    'causes' => [
        'Prolonged immobility',
        'Surgery or injury',
        'Cancer',
        'Pregnancy',
        'Certain genetic clotting disorders',
        'Smoking'
    ],
    'treatments' => [
        'Anticoagulant (blood-thinning) medications',
        'Thrombolytics (in severe cases)',
        'Compression stockings',
        'IVC (inferior vena cava) filter',
        'Lifestyle changes'
    ],
    'prevention' => [
        'Stay active and avoid long periods of immobility',
        'Wear compression stockings',
        'Stay hydrated',
        'Quit smoking',
        'Manage chronic conditions'
    ],
    'specialties' => [
        'Hematologist',
        'Vascular Surgeon',
        'Cardiologist'
    ]
]
,
   'varicoseveins' => [
    'title' => 'Varicose Veins',
    'description' => 'Varicose veins are enlarged, twisted veins that commonly occur in the legs due to weakened or damaged vein valves.',
    'symptoms' => [
        'Visible, bulging veins',
        'Aching or heavy legs',
        'Swelling in the lower legs',
        'Itching around the veins',
        'Muscle cramping and throbbing',
    ],
    'causes' => [
        'Weak or damaged vein valves',
        'Pregnancy',
        'Obesity',
        'Prolonged standing or sitting',
        'Age-related wear and tear',
    ],
    'treatments' => [
        'Compression stockings',
        'Exercise and leg elevation',
        'Sclerotherapy',
        'Laser treatments',
        'Vein stripping surgery',
    ],
    'prevention' => [
        'Regular physical activity',
        'Maintaining a healthy weight',
        'Elevating legs when resting',
        'Avoiding prolonged standing or sitting',
        'Wearing compression garments',
    ],
    'specialties' => [
        'Vascular Surgery',
        'Dermatology',
        'Phlebology'
    ]
],

'epilepsy' => [
    'title' => 'Epilepsy',
    'description' => 'Epilepsy is a neurological disorder characterized by recurrent, unprovoked seizures due to abnormal electrical activity in the brain.',
    'symptoms' => [
        'Temporary confusion',
        'Staring spells',
        'Uncontrollable jerking movements',
        'Loss of consciousness or awareness',
        'Psychic symptoms such as fear or anxiety',
    ],
    'causes' => [
        'Genetic factors',
        'Head trauma',
        'Brain conditions (e.g., tumors, strokes)',
        'Infectious diseases (e.g., meningitis)',
        'Prenatal injury or developmental disorders',
    ],
    'treatments' => [
        'Anti-epileptic medications',
        'Surgical interventions',
        'Vagus nerve stimulation',
        'Ketogenic diet',
        'Responsive neurostimulation devices',
    ],
    'prevention' => [
        'Preventing head injuries',
        'Managing prenatal care',
        'Controlling infections',
        'Avoiding substance abuse',
        'Regular medical check-ups',
    ],
    'specialties' => [
        'Neurology',
        'Neurosurgery',
        'Pediatric Neurology',
    ]
],

'multiplesclerosis' => [
    'title' => 'Multiple Sclerosis',
    'description' => 'Multiple sclerosis (MS) is an autoimmune disease where the immune system attacks the protective sheath (myelin) covering nerve fibers, leading to communication problems between the brain and the rest of the body.',
    'symptoms' => [
        'Numbness or weakness in limbs',
        'Partial or complete vision loss',
        'Tingling or pain sensations',
        'Electric-shock sensations with neck movements',
        'Fatigue and dizziness',
    ],
    'causes' => [
        'Autoimmune response',
        'Genetic susceptibility',
        'Environmental factors (e.g., low vitamin D)',
        'Infections (e.g., Epstein-Barr virus)',
    ],
    'treatments' => [
        'Corticosteroids for relapse management',
        'Disease-modifying therapies (e.g., interferons)',
        'Physical therapy',
        'Muscle relaxants',
        'Plasma exchange (plasmapheresis)',
    ],
    'prevention' => [
        'No known prevention, but risk may be reduced by:',
        'Maintaining adequate vitamin D levels',
        'Avoiding smoking',
        'Early treatment of initial symptoms',
    ],
    'specialties' => [
        'Neurology',
        'Immunology',
        'Physical Medicine and Rehabilitation',
    ]
],

'parkinsonsdisease' => [
    'title' => "Parkinson's Disease",
    'description' => "Parkinson's disease is a progressive nervous system disorder affecting movement, often including tremors.",
    'symptoms' => [
        'Tremors at rest',
        'Slowed movement (bradykinesia)',
        'Muscle stiffness',
        'Impaired posture and balance',
        'Speech and writing changes',
    ],
    'causes' => [
        'Loss of dopamine-producing neurons',
        'Genetic mutations',
        'Environmental triggers (e.g., toxins)',
        'Age-related degeneration',
    ],
    'treatments' => [
        'Medications (e.g., Levodopa)',
        'Deep brain stimulation',
        'Physical and occupational therapy',
        'Lifestyle modifications',
        'Speech therapy',
    ],
    'prevention' => [
        'Regular aerobic exercise',
        'Healthy diet rich in antioxidants',
        'Avoiding exposure to toxins',
        'Monitoring for early symptoms',
    ],
    'specialties' => [
        'Neurology',
        'Geriatrics',
        'Physical Therapy',
    ]
],

'alzheimersdisease' => [
    'title' => "Alzheimer's Disease",
    'description' => "Alzheimer's disease is a progressive neurological disorder that causes brain cells to degenerate and die, leading to a continuous decline in thinking, behavioral, and social skills.",
    'symptoms' => [
        'Memory loss',
        'Difficulty planning or solving problems',
        'Confusion with time or place',
        'Trouble understanding visual images',
        'Changes in mood and personality',
    ],
    'causes' => [
        'Genetic factors',
        'Age-related changes in the brain',
        'Accumulation of amyloid plaques and tau tangles',
        'Cardiovascular disease',
    ],
    'treatments' => [
        'Cholinesterase inhibitors',
        'Memantine for moderate to severe stages',
        'Cognitive training',
        'Supportive therapies',
        'Management of coexisting conditions',
    ],
    'prevention' => [
        'Regular physical activity',
        'Healthy diet (e.g., Mediterranean diet)',
        'Mental stimulation',
        'Social engagement',
        'Managing cardiovascular risk factors',
    ],
    'specialties' => [
        'Neurology',
        'Psychiatry',
        'Geriatrics',
    ]
],

'cerebralpalsy' => [
    'title' => 'Cerebral Palsy',
    'description' => 'Cerebral palsy is a group of disorders affecting movement and muscle tone or posture, caused by damage that occurs to the immature brain as it develops, most often before birth.',
    'symptoms' => [
        'Variations in muscle tone',
        'Stiff muscles and exaggerated reflexes',
        'Lack of muscle coordination',
        'Tremors or involuntary movements',
        'Delays in reaching motor skills milestones',
    ],
    'causes' => [
        'Brain injury before or during birth',
        'Infections during pregnancy',
        'Lack of oxygen to the brain',
        'Traumatic head injury in early childhood',
    ],
    'treatments' => [
        'Physical therapy',
        'Occupational therapy',
        'Speech and language therapy',
        'Medications to control symptoms',
        'Surgical interventions',
    ],
    'prevention' => [
        'Proper prenatal care',
        'Vaccinations to prevent infections',
        'Safety measures to prevent head injuries',
        'Early detection and intervention',
    ],
    'specialties' => [
        'Pediatric Neurology',
        'Physical Medicine and Rehabilitation',
        'Orthopedics',
    ]
],

'bellspalsy' => [
    'title' => "Bell's Palsy",
    'description' => "Bell's palsy is a condition that causes sudden, temporary weakness or paralysis of the muscles on one side of the face.",
    'symptoms' => [
        'Rapid onset of mild weakness to total paralysis on one side of the face',
        'Facial droop and difficulty making facial expressions',
        'Drooling',
        'Pain around the jaw or in or behind the ear',
        'Increased sensitivity to sound on the affected side',
    ],
    'causes' => [
        'Inflammation of the facial nerve',
        'Viral infections (e.g., herpes simplex)',
        'Autoimmune responses',
        'Reduced blood supply to the nerve',
    ],
    'treatments' => [
        'Corticosteroid medications',
        'Antiviral drugs',
        'Physical therapy',
        'Facial massage and exercises',
        'Eye care to prevent dryness',
    ],
    'prevention' => [
        'Prompt treatment of viral infections',
        'Managing stress',
        'Protecting the face from cold exposure',
        'Maintaining overall health and immunity',
    ],
    'specialties' => [
        'Neurology',
        'Otolaryngology',
        'Physical Therapy',
    ]
],

   'sciatica' => [
    'title' => 'Sciatica',
    'description' => 'Sciatica is a condition characterized by pain that radiates along the path of the sciatic nerve, which runs from the lower back through the hips and down each leg.',
    'symptoms' => [
        'Sharp or burning pain in the lower back, buttock, and down one leg',
        'Numbness or tingling in the leg or foot',
        'Muscle weakness in the affected leg',
        'Pain that worsens with prolonged sitting or sudden movements'
    ],
    'causes' => [
        'Herniated or slipped disc in the spine',
        'Spinal stenosis (narrowing of the spinal canal)',
        'Piriformis syndrome (compression of the sciatic nerve by the piriformis muscle)',
        'Degenerative disc disease',
        'Injury or trauma to the spine'
    ],
    'treatments' => [
        'Pain relief medications (NSAIDs or muscle relaxants)',
        'Physical therapy and exercises to improve flexibility and strength',
        'Hot and cold compresses',
        'Steroid injections to reduce inflammation',
        'Surgery in severe or persistent cases (e.g., discectomy, laminectomy)'
    ],
    'prevention' => [
        'Maintain good posture when sitting and standing',
        'Use proper body mechanics when lifting heavy objects',
        'Exercise regularly to strengthen core and back muscles',
        'Avoid prolonged sitting or staying in one position for too long',
        'Maintain a healthy weight to reduce pressure on the spine'
    ],
    'specialties' => [
        'Neurology',
        'Orthopedics',
        'Physical Medicine and Rehabilitation'
    ]
],

    'carpaltunnelsyndrome' => [
    'title' => 'Carpal Tunnel Syndrome',
    'description' => 'Carpal Tunnel Syndrome (CTS) is a condition caused by compression of the median nerve as it travels through the carpal tunnel in the wrist, leading to numbness, tingling, and weakness in the hand.',
    'symptoms' => [
        'Numbness or tingling in the thumb, index, middle, or ring fingers',
        'Hand weakness or clumsiness',
        'Pain or burning sensation in the wrist or forearm',
        'Symptoms worsening at night or with repetitive hand use',
        'Difficulty gripping objects or performing fine motor tasks'
    ],
    'causes' => [
        'Repetitive hand or wrist movements (e.g., typing, assembly line work)',
        'Wrist injuries or fractures',
        'Inflammatory conditions like rheumatoid arthritis',
        'Hormonal changes during pregnancy or menopause',
        'Medical conditions such as diabetes or hypothyroidism',
        'Anatomical factors like a smaller carpal tunnel'
    ],
    'treatments' => [
        'Wrist splinting, especially at night',
        'Nonsteroidal anti-inflammatory drugs (NSAIDs)',
        'Corticosteroid injections to reduce inflammation',
        'Physical therapy and stretching exercises',
        'Surgical intervention (carpal tunnel release) in severe cases'
    ],
    'prevention' => [
        'Maintain proper wrist posture during activities',
        'Take regular breaks from repetitive tasks',
        'Perform hand and wrist stretching exercises',
        'Use ergonomic tools and workstations',
        'Manage underlying health conditions effectively'
    ],
    'specialties' => [
        'Orthopedics',
        'Neurology',
        'Physical Medicine and Rehabilitation'
    ]
],

    'eczema' => [
    'title' => 'Eczema',
    'description' => 'Eczema, also known as atopic dermatitis, is a chronic skin condition that causes inflammation, redness, and itching. It can occur at any age but is more common in children.',
    'symptoms' => [
        'Dry, sensitive skin',
        'Red, inflamed patches',
        'Intense itching',
        'Crusting or oozing in severe cases',
        'Thickened, scaly skin with prolonged scratching'
    ],
    'causes' => [
        'Genetic predisposition',
        'Immune system overreaction',
        'Environmental triggers (e.g., allergens, irritants)',
        'Stress and hormonal changes',
        'Dry skin or harsh soaps'
    ],
    'treatments' => [
        'Moisturizers and emollients',
        'Topical corticosteroids',
        'Antihistamines for itching',
        'Immunosuppressants (in severe cases)',
        'Avoiding known triggers and allergens'
    ],
    'prevention' => [
        'Keep skin moisturized',
        'Avoid harsh soaps and hot water',
        'Wear soft, breathable fabrics',
        'Identify and avoid triggers',
        'Manage stress effectively'
    ],
    'specialties' => [
        'Dermatology',
        'Allergy and Immunology'
    ]
],

'psoriasis' => [
    'title' => 'Psoriasis',
    'description' => 'Psoriasis is a chronic autoimmune condition that causes rapid skin cell production, leading to thick, scaly patches on the skin.',
    'symptoms' => [
        'Red patches with silvery scales',
        'Dry, cracked skin that may bleed',
        'Itching, burning, or soreness',
        'Thickened or ridged nails',
        'Joint pain (in psoriatic arthritis)'
    ],
    'causes' => [
        'Immune system dysfunction',
        'Genetic factors',
        'Infections or skin injuries',
        'Stress and certain medications',
        'Cold, dry weather'
    ],
    'treatments' => [
        'Topical corticosteroids and vitamin D analogs',
        'Phototherapy (UV light)',
        'Systemic medications (e.g., methotrexate, cyclosporine)',
        'Biologic drugs targeting immune responses',
        'Moisturizers and coal tar products'
    ],
    'prevention' => [
        'Avoid known triggers',
        'Keep skin moisturized',
        'Limit alcohol and stop smoking',
        'Reduce stress',
        'Follow prescribed treatment plans'
    ],
    'specialties' => [
        'Dermatology',
        'Rheumatology'
    ]
],

'acne' => [
    'title' => 'Acne',
    'description' => 'Acne is a common skin condition that occurs when hair follicles become clogged with oil and dead skin cells, often leading to pimples, blackheads, and whiteheads.',
    'symptoms' => [
        'Whiteheads and blackheads',
        'Papules and pustules',
        'Cysts and nodules in severe cases',
        'Oily skin',
        'Scarring from inflamed lesions'
    ],
    'causes' => [
        'Excess sebum production',
        'Clogged hair follicles',
        'Bacteria (Propionibacterium acnes)',
        'Hormonal changes',
        'Certain medications or diet'
    ],
    'treatments' => [
        'Topical retinoids and benzoyl peroxide',
        'Oral antibiotics or isotretinoin (Accutane)',
        'Hormonal treatments (e.g., birth control pills)',
        'Chemical peels or laser therapy',
        'Proper skin care routine'
    ],
    'prevention' => [
        'Cleanse face regularly with mild products',
        'Avoid harsh scrubbing',
        'Use non-comedogenic skincare products',
        'Maintain a healthy diet',
        'Avoid touching or picking at acne'
    ],
    'specialties' => [
        'Dermatology'
    ]
],

'rosacea' => [
    'title' => 'Rosacea',
    'description' => 'Rosacea is a chronic skin condition that causes facial redness, visible blood vessels, and sometimes acne-like bumps.',
    'symptoms' => [
        'Facial redness and flushing',
        'Visible blood vessels (telangiectasia)',
        'Bumps and pimples',
        'Eye irritation (ocular rosacea)',
        'Thickening of the skin, especially on the nose'
    ],
    'causes' => [
        'Unknown exact cause, possibly immune and vascular dysfunction',
        'Genetics',
        'Triggers like heat, spicy food, alcohol, or stress',
        'Sun exposure and skin mites'
    ],
    'treatments' => [
        'Topical antibiotics (e.g., metronidazole)',
        'Oral antibiotics for inflammation',
        'Laser therapy for visible vessels',
        'Skin care to reduce irritation',
        'Avoiding known triggers'
    ],
    'prevention' => [
        'Avoid hot drinks, spicy food, and alcohol',
        'Use sunscreen daily',
        'Choose gentle skin care products',
        'Protect face from wind and cold',
        'Manage stress levels'
    ],
    'specialties' => [
        'Dermatology',
        'Ophthalmology (for ocular rosacea)'
    ]
],

'vitiligo' => [
    'title' => 'Vitiligo',
    'description' => 'Vitiligo is a condition where the skin loses pigment due to the destruction or malfunction of melanocytes, resulting in white patches.',
    'symptoms' => [
        'White patches on the skin',
        'Premature whitening of hair',
        'Loss of color inside the mouth or nose',
        'Symmetrical or segmental distribution',
        'Sun sensitivity in affected areas'
    ],
    'causes' => [
        'Autoimmune destruction of melanocytes',
        'Genetic predisposition',
        'Triggering events (e.g., sunburn, stress)',
        'Associated with other autoimmune diseases'
    ],
    'treatments' => [
        'Topical corticosteroids or calcineurin inhibitors',
        'Phototherapy (narrowband UVB)',
        'Depigmentation therapy (for widespread cases)',
        'Skin grafting or micropigmentation',
        'Cosmetic camouflage products'
    ],
    'prevention' => [
        'Protect skin from sun exposure',
        'Manage associated autoimmune conditions',
        'Reduce emotional stress',
        'Avoid skin trauma'
    ],
    'specialties' => [
        'Dermatology',
        'Immunology'
    ]
],

'hives' => [
    'title' => 'Hives',
    'description' => 'Hives, or urticaria, are red, itchy welts on the skin triggered by allergic reactions or other factors.',
    'symptoms' => [
        'Raised, red or skin-colored welts',
        'Itching and burning sensation',
        'Welts that move around or change shape',
        'Swelling of lips, eyelids, or throat (angioedema)',
        'Flares that may last hours or days'
    ],
    'causes' => [
        'Allergic reactions (foods, medications, insect bites)',
        'Infections',
        'Stress or exercise',
        'Temperature extremes (cold or heat)',
        'Autoimmune responses'
    ],
    'treatments' => [
        'Antihistamines',
        'Avoidance of known triggers',
        'Corticosteroids (for severe cases)',
        'Epinephrine for anaphylaxis',
        'Omalizumab (for chronic hives)'
    ],
    'prevention' => [
        'Identify and avoid allergens',
        'Wear protective clothing for physical triggers',
        'Use mild detergents and skincare products',
        'Avoid stress when possible',
        'Maintain a healthy immune system'
    ],
    'specialties' => [
        'Allergy and Immunology',
        'Dermatology'
    ]
],

'shingles' => [
    'title' => 'Shingles',
    'description' => 'Shingles, or herpes zoster, is a viral infection caused by the reactivation of the varicella-zoster virus (which also causes chickenpox). It results in a painful rash, usually on one side of the body.',
    'symptoms' => [
        'Pain, burning, or tingling in a specific area',
        'Red rash that develops into fluid-filled blisters',
        'Itching or sensitivity to touch',
        'Fever, headache, and fatigue',
        'Postherpetic neuralgia (long-term nerve pain after the rash clears)'
    ],
    'causes' => [
        'Reactivation of dormant varicella-zoster virus',
        'Weakened immune system due to age or illness',
        'Stress or trauma',
        'Use of immunosuppressive medications'
    ],
    'treatments' => [
        'Antiviral medications (e.g., acyclovir, valacyclovir)',
        'Pain relievers',
        'Corticosteroids (in some cases)',
        'Calamine lotion or oatmeal baths for itching',
        'Nerve pain medications (e.g., gabapentin) for postherpetic neuralgia'
    ],
    'prevention' => [
        'Shingles vaccine (e.g., Shingrix)',
        'Managing stress and immune health',
        'Avoiding contact with people who have active chickenpox or shingles (especially for immunocompromised individuals)'
    ],
    'specialties' => [
        'Infectious Disease',
        'Dermatology',
        'Neurology'
    ]
],

    'eczema' => [
    'title' => 'Eczema',
    'description' => 'Eczema, also known as atopic dermatitis, is a chronic skin condition that causes inflammation, redness, itching, and dryness of the skin.',
    'symptoms' => [
        'Dry, sensitive skin',
        'Red and inflamed patches',
        'Severe itching',
        'Crusting or oozing in affected areas',
        'Thickened or scaly skin'
    ],
    'causes' => [
        'Genetic predisposition',
        'Immune system dysfunction',
        'Environmental allergens and irritants',
        'Stress',
        'Climate and weather changes'
    ],
    'treatments' => [
        'Moisturizers and emollients',
        'Topical corticosteroids',
        'Antihistamines to reduce itching',
        'Immunosuppressive medications for severe cases',
        'Avoidance of known irritants and allergens'
    ],
    'prevention' => [
        'Regular moisturizing of skin',
        'Using fragrance-free soaps and lotions',
        'Avoiding known triggers',
        'Wearing soft, breathable clothing',
        'Managing stress effectively'
    ],
    'specialties' => [
        'Dermatology',
        'Allergy and Immunology'
    ]
],
'psoriasis' => [
    'title' => 'Psoriasis',
    'description' => 'Psoriasis is a chronic autoimmune skin condition that causes rapid skin cell turnover, resulting in scaling, inflammation, and redness.',
    'symptoms' => [
        'Thick, red patches covered with silvery-white scales',
        'Dry and cracked skin that may bleed',
        'Itching or burning sensations',
        'Stiff or swollen joints (psoriatic arthritis)',
        'Nail changes (pitting, discoloration)'
    ],
    'causes' => [
        'Immune system dysfunction',
        'Genetic predisposition',
        'Infections or skin injuries',
        'Stress or smoking',
        'Certain medications (e.g., beta-blockers, lithium)'
    ],
    'treatments' => [
        'Topical corticosteroids',
        'Vitamin D analogues',
        'Phototherapy (UV light treatment)',
        'Systemic treatments (e.g., methotrexate, biologics)',
        'Moisturizers to reduce dryness'
    ],
    'prevention' => [
        'Avoiding skin trauma and triggers',
        'Maintaining a healthy weight',
        'Managing stress',
        'Limiting alcohol intake',
        'Avoiding smoking'
    ],
    'specialties' => [
        'Dermatology',
        'Rheumatology'
    ]
],
'acne' => [
    'title' => 'Acne',
    'description' => 'Acne is a common skin condition that occurs when hair follicles become clogged with oil and dead skin cells, leading to pimples, blackheads, and cysts.',
    'symptoms' => [
        'Whiteheads and blackheads',
        'Papules and pustules',
        'Nodules and cystic lesions',
        'Oily skin',
        'Scarring in severe cases'
    ],
    'causes' => [
        'Excess oil production',
        'Clogged hair follicles',
        'Bacteria (Cutibacterium acnes)',
        'Hormonal changes (e.g., puberty, menstruation)',
        'Diet and stress'
    ],
    'treatments' => [
        'Topical retinoids and benzoyl peroxide',
        'Oral antibiotics or hormonal therapy',
        'Isotretinoin for severe acne',
        'Salicylic acid-based cleansers',
        'Regular cleansing and exfoliation'
    ],
    'prevention' => [
        'Gentle skin cleansing routine',
        'Avoiding greasy cosmetics or hair products',
        'Using non-comedogenic products',
        'Maintaining a healthy diet',
        'Avoiding frequent touching of the face'
    ],
    'specialties' => [
        'Dermatology'
    ]
],
'rosacea' => [
    'title' => 'Rosacea',
    'description' => 'Rosacea is a chronic skin condition that causes redness, flushing, and visible blood vessels, often on the face, and may produce acne-like bumps.',
    'symptoms' => [
        'Facial redness and flushing',
        'Visible blood vessels (telangiectasia)',
        'Swollen red bumps or pustules',
        'Eye irritation (ocular rosacea)',
        'Thickening of the skin (especially on the nose)'
    ],
    'causes' => [
        'Unknown exact cause',
        'Genetic and environmental factors',
        'Triggers like hot drinks, spicy foods, alcohol, sunlight, and stress'
    ],
    'treatments' => [
        'Topical metronidazole or azelaic acid',
        'Oral antibiotics for inflammation',
        'Laser therapy for visible blood vessels',
        'Skincare products for sensitive skin',
        'Avoidance of known triggers'
    ],
    'prevention' => [
        'Avoiding common rosacea triggers',
        'Using sunscreen daily',
        'Using gentle skincare products',
        'Managing stress',
        'Avoiding hot beverages and alcohol'
    ],
    'specialties' => [
        'Dermatology',
        'Ophthalmology (for ocular rosacea)'
    ]
],
'vitiligo' => [
    'title' => 'Vitiligo',
    'description' => 'Vitiligo is a condition in which the skin loses melanin, resulting in white patches on various parts of the body.',
    'symptoms' => [
        'Pale or white patches on the skin',
        'Symmetrical distribution of patches',
        'Premature whitening of hair or eyelashes',
        'Changes in color of retina or mucous membranes'
    ],
    'causes' => [
        'Autoimmune destruction of melanocytes',
        'Genetic predisposition',
        'Oxidative stress or nerve-related causes',
        'Skin trauma or sunburn may trigger onset'
    ],
    'treatments' => [
        'Topical corticosteroids or calcineurin inhibitors',
        'Phototherapy (narrowband UVB)',
        'Depigmentation for widespread cases',
        'Skin camouflage or cosmetic solutions',
        'Surgical options (e.g., skin grafting) in some cases'
    ],
    'prevention' => [
        'No guaranteed prevention, but early treatment may slow progression',
        'Sun protection to prevent burns on depigmented areas',
        'Avoiding skin trauma'
    ],
    'specialties' => [
        'Dermatology'
    ]
],
'hives' => [
    'title' => 'Hives',
    'description' => 'Hives, or urticaria, are red, itchy welts on the skin caused by an allergic reaction or other triggers.',
    'symptoms' => [
        'Raised, red or skin-colored welts',
        'Itching and burning sensation',
        'Welts that vary in size and shape',
        'Swelling of lips, eyelids, or throat in severe cases (angioedema)',
        'Symptoms that come and go quickly'
    ],
    'causes' => [
        'Allergic reactions to food, medications, or insect stings',
        'Infections',
        'Stress or physical stimuli (heat, cold, pressure)',
        'Autoimmune disorders',
        'Unknown causes (idiopathic)'
    ],
    'treatments' => [
        'Antihistamines to reduce itching and swelling',
        'Avoiding identified triggers',
        'Epinephrine for severe allergic reactions',
        'Corticosteroids in chronic or severe cases',
        'Omalizumab (Xolair) for chronic urticaria'
    ],
    'prevention' => [
        'Identify and avoid known allergens',
        'Keep a symptom diary to detect triggers',
        'Manage stress effectively',
        'Wear loose-fitting clothes',
        'Use gentle soaps and moisturizers'
    ],
    'specialties' => [
        'Allergy and Immunology',
        'Dermatology'
    ]
],
'shingles' => [
    'title' => 'Shingles',
    'description' => 'Shingles, or herpes zoster, is a viral infection that causes a painful rash, typically appearing on one side of the body or face.',
    'symptoms' => [
        'Pain, burning, or tingling in a localized area',
        'Red rash that develops into fluid-filled blisters',
        'Fever, headache, or fatigue',
        'Itching and sensitivity to touch',
        'Postherpetic neuralgia (persistent nerve pain after rash clears)'
    ],
    'causes' => [
        'Reactivation of the varicella-zoster virus (chickenpox virus)',
        'Weakened immune system due to age or illness',
        'Stress or trauma',
        'Certain medications that suppress immunity'
    ],
    'treatments' => [
        'Antiviral medications (e.g., acyclovir, valacyclovir)',
        'Pain relievers and anti-inflammatory drugs',
        'Topical creams or lidocaine patches',
        'Antidepressants or anticonvulsants for nerve pain',
        'Cool compresses and soothing baths'
    ],
    'prevention' => [
        'Vaccination with the shingles vaccine (Shingrix)',
        'Maintaining a healthy immune system',
        'Avoiding contact with people who havent had chickenpox'
    ],
    'specialties' => [
        'Dermatology',
        'Infectious Disease',
        'Neurology'
    ]
],

    'pcos' => [
    'title' => 'Polycystic Ovary Syndrome (PCOS)',
    'description' => 'PCOS is a hormonal disorder common among women of reproductive age, characterized by irregular periods, excess androgen levels, and polycystic ovaries.',
    'symptoms' => ['Irregular menstrual cycles', 'Excess facial and body hair', 'Acne', 'Weight gain', 'Thinning hair on the scalp', 'Infertility'],
    'causes' => ['Hormonal imbalance', 'Genetic factors', 'Insulin resistance'],
    'treatments' => ['Hormonal birth control', 'Anti-androgen medications', 'Lifestyle changes (diet and exercise)', 'Fertility treatments'],
    'prevention' => ['Maintaining a healthy weight', 'Regular exercise', 'Balanced diet'],
    'specialties' => ['Gynecology', 'Endocrinology']
],
'fibroids' => [
    'title' => 'Uterine Fibroids',
    'description' => 'Fibroids are noncancerous growths in the uterus that often appear during childbearing years.',
    'symptoms' => ['Heavy menstrual bleeding', 'Pelvic pain or pressure', 'Frequent urination', 'Constipation', 'Backache or leg pain'],
    'causes' => ['Hormonal factors', 'Genetic mutations', 'Growth factors'],
    'treatments' => ['Medication (hormonal therapy)', 'Noninvasive procedures', 'Surgical removal (myomectomy)', 'Hysterectomy'],
    'prevention' => ['Regular gynecological checkups', 'Healthy lifestyle choices'],
    'specialties' => ['Gynecology']
],
'menopause' => [
    'title' => 'Menopause',
    'description' => 'Menopause marks the end of a womans menstrual cycles and fertility, typically occurring in midlife.',
    'symptoms' => ['Hot flashes', 'Night sweats', 'Mood changes', 'Vaginal dryness', 'Sleep problems'],
    'causes' => ['Natural decline in reproductive hormones', 'Surgical removal of ovaries', 'Chemotherapy or radiation'],
    'treatments' => ['Hormone replacement therapy (HRT)', 'Lifestyle changes', 'Non-hormonal medications'],
    'prevention' => ['Healthy diet', 'Regular exercise', 'Avoiding smoking'],
    'specialties' => ['Gynecology', 'Endocrinology']
],
'osteoporosis' => [
    'title' => 'Osteoporosis',
    'description' => 'Osteoporosis is a condition that weakens bones, making them fragile and more likely to break.',
    'symptoms' => ['Back pain', 'Loss of height over time', 'Stooped posture', 'Easily broken bones'],
    'causes' => ['Aging', 'Hormonal changes', 'Calcium or vitamin D deficiency'],
    'treatments' => ['Bisphosphonates', 'Calcium and vitamin D supplements', 'Hormone-related therapy', 'Weight-bearing exercises'],
    'prevention' => ['Adequate calcium and vitamin D intake', 'Regular exercise', 'Avoiding tobacco and excessive alcohol'],
    'specialties' => ['Endocrinology', 'Rheumatology']
],
'cervicalcancer' => [
    'title' => 'Cervical Cancer',
    'description' => 'Cervical cancer occurs in the cells of the cervix, often linked to HPV infection.',
    'symptoms' => ['Abnormal vaginal bleeding', 'Pelvic pain', 'Pain during intercourse', 'Unusual vaginal discharge'],
    'causes' => ['Persistent HPV infection', 'Smoking', 'Weakened immune system'],
    'treatments' => ['Surgery', 'Radiation therapy', 'Chemotherapy'],
    'prevention' => ['HPV vaccination', 'Regular Pap tests', 'Safe sexual practices'],
    'specialties' => ['Gynecologic Oncology']
],
'prostatecancer' => [
    'title' => 'Prostate Cancer',
    'description' => 'Prostate cancer is a common cancer in men that begins in the prostate gland.',
    'symptoms' => ['Difficulty urinating', 'Frequent urination', 'Blood in urine or semen', 'Pain in the back, hips, or pelvis'],
    'causes' => ['Age', 'Genetic factors', 'Hormonal imbalance'],
    'treatments' => ['Surgery (prostatectomy)', 'Radiation therapy', 'Hormone therapy', 'Chemotherapy'],
    'prevention' => ['Healthy diet', 'Regular screenings', 'Exercise'],
    'specialties' => ['Urology', 'Oncology']
],
'erectiledysfunction' => [
    'title' => 'Erectile Dysfunction',
    'description' => 'Erectile dysfunction (ED) is the inability to get or keep an erection firm enough for sexual intercourse.',
    'symptoms' => ['Difficulty achieving erection', 'Difficulty maintaining erection', 'Reduced sexual desire'],
    'causes' => ['Cardiovascular disease', 'Diabetes', 'Psychological factors', 'Hormonal imbalance'],
    'treatments' => ['Oral medications (e.g., Viagra)', 'Psychotherapy', 'Lifestyle changes', 'Hormone therapy'],
    'prevention' => ['Healthy lifestyle', 'Managing underlying conditions', 'Avoiding tobacco and alcohol'],
    'specialties' => ['Urology', 'Endocrinology']
],

   'benignprostatichyperplasia' => [
    'title' => 'Benign Prostatic Hyperplasia (BPH)',
    'description' => 'BPH is a non-cancerous enlargement of the prostate gland that commonly affects older men, leading to urinary symptoms.',
    'symptoms' => [
        'Frequent urination, especially at night',
        'Difficulty starting urination',
        'Weak urine stream',
        'Inability to completely empty the bladder',
        'Urgent need to urinate'
    ],
    'causes' => [
        'Age-related hormonal changes',
        'Increased levels of dihydrotestosterone (DHT)',
        'Family history of prostate problems'
    ],
    'treatments' => [
        'Alpha-blockers (to relax prostate muscles)',
        '5-alpha reductase inhibitors (to shrink the prostate)',
        'Minimally invasive procedures',
        'Surgery (e.g., TURP)'
    ],
    'prevention' => [
        'Regular physical activity',
        'Healthy diet',
        'Limiting caffeine and alcohol',
        'Regular prostate checkups'
    ],
    'specialties' => ['Urology']
],
'testicularcancer' => [
    'title' => 'Testicular Cancer',
    'description' => 'Testicular cancer is a type of cancer that originates in the testicles and is most common in young and middle-aged men.',
    'symptoms' => [
        'Lump or swelling in the testicle',
        'A feeling of heaviness in the scrotum',
        'Dull ache in the groin or abdomen',
        'Sudden collection of fluid in the scrotum',
        'Pain or discomfort in a testicle'
    ],
    'causes' => [
        'Undescended testicle (cryptorchidism)',
        'Family history of testicular cancer',
        'HIV infection',
        'Abnormal testicular development'
    ],
    'treatments' => [
        'Surgical removal of the testicle (orchiectomy)',
        'Radiation therapy',
        'Chemotherapy',
        'Surveillance for early-stage cases'
    ],
    'prevention' => [
        'Regular testicular self-exams',
        'Awareness of risk factors',
        'Early medical consultation if changes are noticed'
    ],
    'specialties' => ['Urology', 'Oncology']
],
'maleinfertility' => [
    'title' => 'Male Infertility',
    'description' => 'Male infertility refers to a mans inability to cause pregnancy in a fertile female partner due to various issues like sperm production, motility, or blockages.',
    'symptoms' => [
        'Problems with sexual function',
        'Pain or swelling in the testicle area',
        'Reduced facial or body hair',
        'Low sperm count (discovered via testing)'
    ],
    'causes' => [
        'Varicocele (enlarged veins in the scrotum)',
        'Infections (e.g., STIs, mumps)',
        'Hormonal imbalances',
        'Genetic defects',
        'Environmental toxins',
        'Lifestyle factors (smoking, alcohol, stress)'
    ],
    'treatments' => [
        'Lifestyle changes',
        'Hormonal therapy',
        'Surgical correction of varicocele or blockages',
        'Assisted reproductive techniques (e.g., IVF, ICSI)'
    ],
    'prevention' => [
        'Avoiding tobacco and drug use',
        'Maintaining a healthy weight',
        'Protecting testicles from injury',
        'Limiting exposure to toxins and heat'
    ],
    'specialties' => ['Urology', 'Endocrinology', 'Reproductive Medicine']
]

];

// Debug available conditions
$availableConditions = array_keys($details);
error_log("Available conditions in details array: " . implode(", ", $availableConditions));

// Check if condition exists
if (!isset($details[$condition])) {
    error_log("Condition not found: " . $condition);
    error_log("Available conditions are: " . implode(", ", $availableConditions));
}

// Get the condition info
$info = isset($details[$condition]) ? $details[$condition] : [
    'title' => 'Condition Not Found',
    'description' => 'Sorry, we could not find information about this condition. Condition requested: ' . htmlspecialchars($condition) . 
                    '<br>Available conditions are: ' . implode(", ", $availableConditions),
    'symptoms' => [],
    'causes' => [],
    'treatments' => [],
    'prevention' => [],
    'specialties' => []
];

// Debug the selected info
error_log("Selected condition info: " . print_r($info, true));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= htmlspecialchars($info['title']) ?> - HealthServe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .info-card {
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .info-card:hover {
            transform: translateY(-5px);
        }
        .specialty-card {
            border-left: 4px solid #0d6efd;
        }
        .list-item {
            margin-bottom: 0.5rem;
            padding-left: 1.5rem;
            position: relative;
        }
        .list-item:before {
            content: "•";
            position: absolute;
            left: 0;
            color: #0d6efd;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-1 shadow-sm">
        <div class="container-fluid px-3 px-md-4">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <div class="d-flex align-items-center">
                    <img src="img/imgSoloLogo.png" alt="Logo" class="me-2" style="height: 50px;">
                    <img src="img/imgSoloTitle.png" alt="Title" class="pt-2" style="height: 40px;">
                </div>
            </a>
            
        </div>
    </nav>

    <div class="container py-5">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <div class="bg-white p-4 rounded-3 shadow-sm mb-4">
                    <h1 class="mb-4"><?= htmlspecialchars($info['title']) ?></h1>
                    <p class="lead mb-4"><?= htmlspecialchars($info['description']) ?></p>

                    <!-- Symptoms Section -->
                    <div class="mb-4">
                        <h3 class="h4 mb-3"><i class="bi bi-exclamation-circle me-2"></i>Symptoms</h3>
                        <ul class="list-unstyled">
                            <?php foreach ($info['symptoms'] as $symptom): ?>
                                <li class="list-item"><?= htmlspecialchars($symptom) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Causes Section -->
                    <div class="mb-4">
                        <h3 class="h4 mb-3"><i class="bi bi-question-circle me-2"></i>Causes</h3>
                        <ul class="list-unstyled">
                            <?php foreach ($info['causes'] as $cause): ?>
                                <li class="list-item"><?= htmlspecialchars($cause) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Treatments Section -->
                    <div class="mb-4">
                        <h3 class="h4 mb-3"><i class="bi bi-heart-pulse me-2"></i>Treatments</h3>
                        <ul class="list-unstyled">
                            <?php foreach ($info['treatments'] as $treatment): ?>
                                <li class="list-item"><?= htmlspecialchars($treatment) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Prevention Section -->
                    <div class="mb-4">
                        <h3 class="h4 mb-3"><i class="bi bi-shield-check me-2"></i>Prevention</h3>
                        <ul class="list-unstyled">
                            <?php foreach ($info['prevention'] as $prevention): ?>
                                <li class="list-item"><?= htmlspecialchars($prevention) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Specialties Section -->
                <div class="bg-white p-4 rounded-3 shadow-sm mb-4">
                    <h3 class="h4 mb-4"><i class="bi bi-person-badge me-2"></i>Recommended Specialties</h3>
                    <div class="row g-3">
                        <?php foreach ($info['specialties'] as $specialty): ?>
                            <div class="col-12">
                                <div class="p-3 specialty-card bg-light rounded">
                                    <h5 class="mb-0"><?= htmlspecialchars($specialty) ?></h5>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-grid gap-2">
                    <a href="conditions.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Conditions
                    </a>
                    <a href="doctors.php" class="btn btn-primary">
                        <i class="bi bi-search me-2"></i>Find a Doctor
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

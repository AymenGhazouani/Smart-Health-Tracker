<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Symptom Checker</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --emergency: #dc2626;
            --light: #f8fafc;
            --dark: #1e293b;
            --border: #e2e8f0;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            color: white;
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow);
            padding: 30px;
            margin-bottom: 30px;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .main-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        @media (max-width: 768px) {
            .main-content {
                grid-template-columns: 1fr;
            }
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }

        .input-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
        }

        textarea {
            min-height: 120px;
            resize: vertical;
            font-family: inherit;
        }

        .btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn:disabled {
            background: var(--secondary);
            cursor: not-allowed;
            transform: none;
        }

        .btn-emergency {
            background: var(--emergency);
        }

        .btn-emergency:hover {
            background: #b91c1c;
        }

        .results-section {
            display: none;
        }

        .urgency-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .urgency-emergency {
            background: #fee2e2;
            color: var(--emergency);
            border: 2px solid var(--emergency);
        }

        .urgency-high {
            background: #fef3c7;
            color: var(--warning);
            border: 2px solid var(--warning);
        }

        .urgency-medium {
            background: #dbeafe;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .urgency-low {
            background: #dcfce7;
            color: var(--success);
            border: 2px solid var(--success);
        }

        .symptoms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }

        .symptom-card {
            background: var(--light);
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid var(--primary);
        }

        .provider-card {
            background: white;
            border: 2px solid var(--border);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            transition: border-color 0.3s ease;
        }

        .provider-card:hover {
            border-color: var(--primary);
        }

        .confidence-bar {
            height: 8px;
            background: var(--border);
            border-radius: 4px;
            margin: 10px 0;
            overflow: hidden;
        }

        .confidence-fill {
            height: 100%;
            background: var(--primary);
            border-radius: 4px;
            transition: width 0.5s ease;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 40px;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .history-link {
            text-align: center;
            margin-top: 20px;
        }

        .history-link a {
            color: white;
            text-decoration: none;
            font-weight: 500;
        }

        .history-link a:hover {
            text-decoration: underline;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }

        .stat-card {
            background: var(--light);
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--secondary);
        }

        .advice-box {
            background: #fffbeb;
            border: 2px solid #f59e0b;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
        }

        .error-message {
            background: #fee2e2;
            border: 2px solid var(--danger);
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            color: var(--danger);
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1><i class="fas fa-stethoscope"></i> Advanced Symptom Checker</h1>
        <p>AI-powered symptom analysis and provider recommendations</p>
    </div>

    <div class="main-content">
        <!-- Input Section -->
        <div class="card">
            <h2 style="margin-bottom: 20px; color: var(--dark);">
                <i class="fas fa-notes-medical"></i> Describe Your Symptoms
            </h2>

            <form id="symptomForm">
                @csrf

                <div class="form-group">
                    <label for="symptoms">
                        <i class="fas fa-comment-medical"></i> Symptoms Description *
                    </label>
                    <textarea
                        id="symptoms"
                        name="symptoms"
                        placeholder="Describe your symptoms in detail. For example: 'I have been experiencing chest pain and shortness of breath for the past 2 days...'"
                        required
                    ></textarea>
                </div>

                <div class="input-group">
                    <div class="form-group">
                        <label for="age"><i class="fas fa-birthday-cake"></i> Age</label>
                        <input type="number" id="age" name="age" placeholder="Enter your age" min="0" max="120">
                    </div>

                    <div class="form-group">
                        <label for="gender"><i class="fas fa-user"></i> Gender</label>
                        <select id="gender" name="gender">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn" id="analyzeBtn">
                    <i class="fas fa-search"></i> Analyze Symptoms
                </button>
            </form>
        </div>

        <!-- Results Section -->
        <div class="card results-section" id="resultsSection">
            <h2 style="margin-bottom: 20px; color: var(--dark);">
                <i class="fas fa-chart-line"></i> Analysis Results
            </h2>

            <div id="urgencySection"></div>
            <div id="resultsContent"></div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div class="card loading" id="loadingIndicator">
        <div class="spinner"></div>
        <p>Analyzing your symptoms with AI...</p>
    </div>

    <!-- History Link -->
    <div class="history-link">
        <a href="{{ route('symptom-history') }}">
            <i class="fas fa-history"></i> View Analysis History
        </a>
    </div>
</div>

<script>
    document.getElementById('symptomForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const analyzeBtn = document.getElementById('analyzeBtn');
        const loadingIndicator = document.getElementById('loadingIndicator');
        const resultsSection = document.getElementById('resultsSection');
        const urgencySection = document.getElementById('urgencySection');
        const resultsContent = document.getElementById('resultsContent');

        // Show loading, hide results
        analyzeBtn.disabled = true;
        analyzeBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Analyzing...';
        loadingIndicator.style.display = 'block';
        resultsSection.style.display = 'none';

        try {
            const formData = new FormData(this);

            const response = await fetch('{{ route("analyze-symptoms") }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.error || 'Analysis failed');
            }

            // Display results
            displayResults(data);

        } catch (error) {
            showError(error.message);
        } finally {
            analyzeBtn.disabled = false;
            analyzeBtn.innerHTML = '<i class="fas fa-search"></i> Analyze Symptoms';
            loadingIndicator.style.display = 'none';
        }
    });

    function displayResults(data) {
        const resultsSection = document.getElementById('resultsSection');
        const urgencySection = document.getElementById('urgencySection');
        const resultsContent = document.getElementById('resultsContent');

        // Urgency display
        const urgencyClass = `urgency-${data.urgency_level}`;
        const urgencyIcons = {
            emergency: 'fas fa-exclamation-triangle',
            high: 'fas fa-exclamation-circle',
            medium: 'fas fa-info-circle',
            low: 'fas fa-check-circle'
        };

        urgencySection.innerHTML = `
                <div class="urgency-badge ${urgencyClass}">
                    <i class="${urgencyIcons[data.urgency_level]}"></i>
                    ${data.urgency_level.toUpperCase()} URGENCY (${(data.urgency_score * 100).toFixed(1)}%)
                </div>
            `;

        // Results content
        let content = `
                <div class="stats">
                    <div class="stat-card">
                        <div class="stat-value">${data.matched_symptoms.length}</div>
                        <div class="stat-label">Symptoms Matched</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">${data.recommended_providers.length}</div>
                        <div class="stat-label">Providers Recommended</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">${data.processing_time}s</div>
                        <div class="stat-label">Processing Time</div>
                    </div>
                </div>

                <div class="advice-box">
                    <h3 style="margin-bottom: 10px; color: #d97706;">
                        <i class="fas fa-lightbulb"></i> Medical Advice
                    </h3>
                    <p>${data.general_advice}</p>
                </div>
            `;

        // Matched Symptoms
        if (data.matched_symptoms.length > 0) {
            content += `
                    <h3 style="margin: 30px 0 15px 0; color: var(--dark);">
                        <i class="fas fa-list-check"></i> Matched Symptoms
                    </h3>
                    <div class="symptoms-grid">
                        ${data.matched_symptoms.map(symptom => `
                            <div class="symptom-card">
                                <strong>${symptom.symptom}</strong>
                                <div style="margin-top: 5px; font-size: 0.875rem; color: var(--secondary);">
                                    Confidence: ${(symptom.confidence * 100).toFixed(1)}%
                                </div>
                            </div>
                        `).join('')}
                    </div>
                `;
        }

        // Provider Recommendations
        if (data.recommended_providers.length > 0) {
            content += `
                    <h3 style="margin: 30px 0 15px 0; color: var(--dark);">
                        <i class="fas fa-user-md"></i> Recommended Providers
                    </h3>
                    ${data.recommended_providers.map(provider => `
                        <div class="provider-card">
                            <div style="display: flex; justify-content: between; align-items: start; margin-bottom: 10px;">
                                <h4 style="flex: 1; margin: 0; color: var(--primary);">${provider.specialty}</h4>
                                <span style="background: var(--primary); color: white; padding: 4px 12px; border-radius: 12px; font-size: 0.875rem;">
                                    ${(provider.confidence * 100).toFixed(1)}% Match
                                </span>
                            </div>
                            <div class="confidence-bar">
                                <div class="confidence-fill" style="width: ${provider.match_score * 100}%"></div>
                            </div>
                            <p style="margin: 10px 0; color: var(--secondary);">${provider.reasoning}</p>
                            <div style="font-size: 0.875rem;">
                                <strong>Relevant Symptoms:</strong> ${provider.relevant_symptoms.join(', ')}
                            </div>
                        </div>
                    `).join('')}
                `;
        }

        // Query ID
        content += `
                <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border);">
                    <small style="color: var(--secondary);">Analysis ID: ${data.query_id}</small>
                </div>
            `;

        resultsContent.innerHTML = content;
        resultsSection.style.display = 'block';

        // Scroll to results
        resultsSection.scrollIntoView({ behavior: 'smooth' });
    }

    function showError(message) {
        const resultsSection = document.getElementById('resultsSection');
        const resultsContent = document.getElementById('resultsContent');

        resultsContent.innerHTML = `
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <strong>Error:</strong> ${message}
                </div>
            `;
        resultsSection.style.display = 'block';
        resultsSection.scrollIntoView({ behavior: 'smooth' });
    }
</script>
</body>
</html>

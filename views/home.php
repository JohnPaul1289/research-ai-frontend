<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research AI — Intelligent Academic Research Platform</title>
    <meta name="description" content="Research AI is the ultimate intelligent agent platform for thesis and capstone research. Powered by multi-agent AI technology.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-glow: rgba(99, 102, 241, 0.5);
            --secondary: #a855f7;
            --bg-dark: #030712;
            --glass-bg: rgba(17, 24, 39, 0.6);
            --glass-border: rgba(255, 255, 255, 0.08);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            font-family: 'Outfit', sans-serif;
            overflow-x: hidden;
        }

        /* Ambient Background */
        .orb { position: fixed; border-radius: 50%; filter: blur(120px); opacity: 0.3; z-index: 0; animation: float 20s infinite ease-in-out alternate; }
        .orb-1 { width: 600px; height: 600px; background: var(--primary); top: -15%; left: -5%; }
        .orb-2 { width: 500px; height: 500px; background: var(--secondary); bottom: 0; right: -10%; animation-delay: -7s; }
        .orb-3 { width: 350px; height: 350px; background: #ec4899; top: 50%; left: 50%; animation-delay: -12s; opacity: 0.15; }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(40px, 25px) scale(1.05); }
            100% { transform: translate(-30px, 50px) scale(0.95); }
        }

        /* Navigation */
        .navbar-custom {
            position: fixed; top: 0; width: 100%; z-index: 1000;
            background: rgba(3, 7, 18, 0.6);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
            padding: 1rem 2rem;
        }

        .navbar-custom .brand { font-weight: 800; font-size: 1.3rem; color: white; text-decoration: none; display: flex; align-items: center; gap: 10px; }
        .navbar-custom .brand-icon { width: 32px; height: 32px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px; }

        .nav-links { display: flex; gap: 2rem; align-items: center; }
        .nav-links a { color: var(--text-muted); text-decoration: none; font-weight: 500; transition: color 0.3s; }
        .nav-links a:hover { color: white; }
        .nav-links .btn-nav {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white; padding: 8px 24px; border-radius: 10px; font-weight: 600;
            box-shadow: 0 5px 15px rgba(99, 102, 241, 0.3); transition: all 0.3s;
        }
        .nav-links .btn-nav:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(99, 102, 241, 0.4); color: white; }

        /* Hero Section */
        .hero {
            position: relative; z-index: 10;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            text-align: center; padding: 120px 20px 80px;
        }

        .hero-content { max-width: 850px; }

        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(99, 102, 241, 0.1); border: 1px solid rgba(99, 102, 241, 0.2);
            padding: 6px 16px; border-radius: 30px;
            color: var(--primary); font-size: 0.85rem; font-weight: 600;
            margin-bottom: 2rem;
            animation: fadeInDown 1s ease forwards;
        }

        .hero h1 {
            font-size: clamp(2.5rem, 6vw, 4.5rem);
            font-weight: 900; line-height: 1.1;
            margin-bottom: 1.5rem;
            animation: fadeInUp 1s ease forwards;
        }

        .text-gradient {
            background: linear-gradient(135deg, var(--primary), var(--secondary), #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-size: 200% auto;
            animation: gradientShift 5s ease infinite;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .hero p {
            font-size: 1.25rem; color: var(--text-muted);
            max-width: 600px; margin: 0 auto 2.5rem;
            line-height: 1.7;
            animation: fadeInUp 1s 0.2s ease forwards;
            opacity: 0;
        }

        .hero-cta {
            display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;
            animation: fadeInUp 1s 0.4s ease forwards;
            opacity: 0;
        }

        .btn-hero-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white; padding: 14px 36px; border-radius: 14px; font-weight: 700;
            font-size: 1.05rem; text-decoration: none;
            box-shadow: 0 10px 25px var(--primary-glow);
            transition: all 0.3s;
        }
        .btn-hero-primary:hover { transform: translateY(-3px); box-shadow: 0 15px 30px var(--primary-glow); color: white; }

        .btn-hero-secondary {
            background: transparent;
            border: 1px solid var(--glass-border);
            color: var(--text-main); padding: 14px 36px; border-radius: 14px;
            font-weight: 600; font-size: 1.05rem; text-decoration: none;
            transition: all 0.3s;
        }
        .btn-hero-secondary:hover { background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.15); color: white; }

        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }

        /* Stats Section */
        .stats-section {
            position: relative; z-index: 10;
            padding: 0 20px 80px;
        }
        .stats-grid {
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px;
            max-width: 900px; margin: 0 auto;
        }
        .stat-card {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 2rem 1.5rem;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.4); }
        .stat-number { font-size: 2.5rem; font-weight: 900; margin-bottom: 0.3rem; background: linear-gradient(to right, #fff, var(--text-muted)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .stat-label { color: var(--text-muted); font-size: 0.9rem; font-weight: 500; }

        /* Features Section */
        .features-section {
            position: relative; z-index: 10;
            padding: 80px 20px;
        }
        .section-title { text-align: center; font-size: 2.5rem; font-weight: 800; margin-bottom: 1rem; }
        .section-subtitle { text-align: center; color: var(--text-muted); font-size: 1.1rem; margin-bottom: 4rem; max-width: 600px; margin-left: auto; margin-right: auto; }

        .features-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;
            max-width: 1100px; margin: 0 auto;
        }
        .feature-card {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 2.5rem 2rem;
            transition: transform 0.3s, box-shadow 0.3s;
            animation: floatCard 6s infinite ease-in-out;
        }
        .feature-card:hover { 
            transform: translateY(-12px) scale(1.02) !important; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.4), 0 0 20px rgba(99, 102, 241, 0.2); 
            animation-play-state: paused; 
        }
        
        .feature-card:nth-child(1) { animation-delay: 0s; }
        .feature-card:nth-child(2) { animation-delay: 1s; }
        .feature-card:nth-child(3) { animation-delay: 2s; }
        .feature-card:nth-child(4) { animation-delay: 1.5s; }
        .feature-card:nth-child(5) { animation-delay: 0.5s; }
        .feature-card:nth-child(6) { animation-delay: 2.5s; }

        @keyframes floatCard {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        .feature-icon {
            width: 56px; height: 56px; border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; margin-bottom: 1.5rem;
        }
        .feature-card h3 { font-size: 1.2rem; font-weight: 700; margin-bottom: 0.8rem; }
        .feature-card p { color: var(--text-muted); font-size: 0.95rem; line-height: 1.6; }

        /* How It Works */
        .how-section {
            position: relative; z-index: 10;
            padding: 80px 20px;
        }
        .steps-grid {
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px;
            max-width: 1100px; margin: 0 auto;
        }
        .step-card {
            text-align: center;
            padding: 2rem 1.5rem;
        }
        .step-number {
            width: 52px; height: 52px; margin: 0 auto 1.2rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 1.3rem;
            box-shadow: 0 8px 20px var(--primary-glow);
        }
        .step-card h4 { font-weight: 700; margin-bottom: 0.5rem; }
        .step-card p { color: var(--text-muted); font-size: 0.9rem; line-height: 1.5; }

        /* Footer */
        .footer {
            position: relative; z-index: 10;
            border-top: 1px solid var(--glass-border);
            padding: 3rem 2rem;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .features-grid { grid-template-columns: 1fr; }
            .steps-grid { grid-template-columns: repeat(2, 1fr); }
            .nav-links { gap: 1rem; }
        }
    </style>
</head>
<body>

    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <!-- Navigation -->
    <nav class="navbar-custom">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="/" class="brand">
                <div class="brand-icon" style="padding:0; overflow:hidden;"><img src="/assets/img/logo.png" style="width:100%;height:100%;object-fit:cover;"></div>
                Research AI
            </a>
            <div class="nav-links">
                <a href="/login">Sign In</a>
                <a href="/register" class="btn-nav">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-badge">
                <i class="fa-solid fa-sparkles"></i> Multi-Agent AI Platform
            </div>
            <h1>The Future of<br><span class="text-gradient">Academic Research</span></h1>
            <p>Powered by 15 specialized AI agents working together to help you formulate topics, analyze literature, write chapters, and defend your thesis.</p>
            <div class="hero-cta">
                <a href="/register" class="btn-hero-primary">
                    Start Your Research <i class="fa-solid fa-arrow-right ms-2"></i>
                </a>
                <a href="/login" class="btn-hero-secondary">
                    <i class="fa-solid fa-play me-2"></i> Sign In
                </a>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?= number_format($stats['users']) ?>+</div>
                <div class="stat-label">Active Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= number_format($stats['projects']) ?>+</div>
                <div class="stat-label">Research Projects</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['agents'] ?></div>
                <div class="stat-label">AI Agents</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['uptime'] ?></div>
                <div class="stat-label">Uptime</div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="features-section">
        <h2 class="section-title">Why Research AI?</h2>
        <p class="section-subtitle">Everything you need to complete your thesis, powered by cutting-edge artificial intelligence.</p>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon" style="background: rgba(99,102,241,0.1); color: var(--primary);"><i class="fa-solid fa-robot"></i></div>
                <h3>Multi-Agent System</h3>
                <p>15 specialized AI agents collaborate to handle every aspect of your research — from topic formulation to statistical analysis.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background: rgba(168,85,247,0.1); color: var(--secondary);"><i class="fa-solid fa-magnifying-glass-chart"></i></div>
                <h3>Smart Literature Review</h3>
                <p>Automatically scan, summarize, and cross-reference thousands of academic papers from Google Scholar, ArXiv, and more.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background: rgba(236,72,153,0.1); color: #ec4899;"><i class="fa-solid fa-pen-nib"></i></div>
                <h3>Chapter Writing Assistant</h3>
                <p>AI-guided writing with proper APA formatting, citations, and academic tone. Write chapters 10x faster.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background: rgba(16,185,129,0.1); color: #10b981;"><i class="fa-solid fa-chart-line"></i></div>
                <h3>Data Analysis Engine</h3>
                <p>Upload your datasets and get instant statistical analysis, visualizations, and interpretation of results.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background: rgba(245,158,11,0.1); color: #f59e0b;"><i class="fa-solid fa-shield-halved"></i></div>
                <h3>Plagiarism Guard</h3>
                <p>Built-in plagiarism detection ensures your work is original and properly cited before submission.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background: rgba(59,130,246,0.1); color: #3b82f6;"><i class="fa-solid fa-users-gear"></i></div>
                <h3>Advisor Collaboration</h3>
                <p>Real-time collaboration tools let your research adviser review, comment, and guide your work seamlessly.</p>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-section">
        <h2 class="section-title">How It Works</h2>
        <p class="section-subtitle">From registration to thesis defense in four simple steps.</p>
        <div class="steps-grid">
            <div class="step-card">
                <div class="step-number">1</div>
                <h4>Create Account</h4>
                <p>Register as a Student, Faculty, or Research Adviser and set up your workspace.</p>
            </div>
            <div class="step-card">
                <div class="step-number">2</div>
                <h4>Start a Project</h4>
                <p>Define your research topic, department, and methodology. AI will help refine it.</p>
            </div>
            <div class="step-card">
                <div class="step-number">3</div>
                <h4>Let AI Assist</h4>
                <p>Chat with specialized agents to analyze data, write chapters, and review literature.</p>
            </div>
            <div class="step-card">
                <div class="step-number">4</div>
                <h4>Submit & Defend</h4>
                <p>Get your final manuscript reviewed, formatted, and ready for panel defense.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2026 Research AI. Built with <i class="fa-solid fa-heart" style="color: #ef4444;"></i> for academic excellence.</p>
    </footer>

</body>
</html>

 <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #00d4aa 0%, #38ef7d 100%);
            --danger-gradient: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
            --warning-gradient: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            --info-gradient: linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%);
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.15);
            --shadow-light: 0 8px 32px rgba(31, 38, 135, 0.15);
            --shadow-hover: 0 15px 45px rgba(31, 38, 135, 0.25);
        }

        .page-wrapper {
            background: var(--primary-gradient);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            position: relative;
            overflow-x: hidden;
        }

        .page-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.05"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.05"/><circle cx="50" cy="50" r="1" fill="white" opacity="0.03"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            pointer-events: none;
        }

        .content {
            padding: 2rem 3% !important;
            position: relative;
            z-index: 1;
        }

        .dashboard-title {
            text-align: center;
            color: white;
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 3rem;
            text-shadow: 0 4px 20px rgba(0,0,0,0.4);
            background: linear-gradient(45deg, #fff, #e0e7ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .filter-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 2rem;
            margin-bottom: 3rem;
            box-shadow: var(--shadow-light);
        }

        .filter-form {
            display: flex;
            flex-wrap: wrap;
            align-items: end;
            gap: 1.5rem;
        }

        .form-group {
            flex: 1;
            min-width: 150px;
        }

        .form-group label {
            display: block;
            color: white;
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: white;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.4);
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
            color: white;
        }

        .form-control option {
            background: #2d3748;
            color: white;
        }

        .btn-filter {
            background: linear-gradient(135deg, #4299e1, #3182ce);
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(66, 153, 225, 0.3);
        }

        .btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(66, 153, 225, 0.4);
        }

        .metrics-section {
            margin-bottom: 3rem;
        }

        .section-title {
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .metric-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-light);
        }

        .metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: var(--success-gradient);
            transition: all 0.3s ease;
        }

        .metric-card.success::before { background: var(--success-gradient); }
        .metric-card.danger::before { background: var(--danger-gradient); }
        .metric-card.warning::before { 
            background: linear-gradient(135deg, #fbbf24, #f59e0b); 
            color: #92400e;
        }
        .metric-card.info::before { background: var(--info-gradient); }

        .metric-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: var(--shadow-hover);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .card-title {
            color: white !important;
            font-size: 1rem;
            font-weight: 500;
            margin: 0;
            opacity: 0.85;
            line-height: 1.4;
        }

        .card-icon {
            font-size: 2.5rem;
            opacity: 0.15;
            filter: blur(0.5px);
        }

        .card-value {
            color: white !important;
            font-size: 2.2rem;
            font-weight: 700;
            margin: 0.5rem 0;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
            line-height: 1.2;
        }

        .card-subtitle {
            color: rgba(255,255,255,0.6);
            font-size: 0.85rem;
            font-weight: 400;
            margin: 0;
        }

        .chart-container {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 2.5rem;
            margin-top: 2rem;
            box-shadow: var(--shadow-light);
            transition: all 0.3s ease;
        }

        .chart-container:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-hover);
        }

        .chart-title {
            color: white;
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 2rem;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .content {
                padding: 2rem 2% !important;
            }
        }

        @media (max-width: 768px) {
            .dashboard-title {
                font-size: 2.2rem;
            }
            
            .filter-form {
                flex-direction: column;
                align-items: stretch;
            }
            
            .card-value {
                font-size: 1.8rem;
            }
            
            .metric-card {
                padding: 1.5rem;
            }
            
            .chart-container {
                padding: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .dashboard-title {
                font-size: 1.8rem;
            }
            
            .card-value {
                font-size: 1.5rem;
            }
        }

        /* Animações */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .metric-card {
            animation: slideInUp 0.6s ease-out;
        }

        .metric-card:nth-child(1) { animation-delay: 0.1s; }
        .metric-card:nth-child(2) { animation-delay: 0.2s; }
        .metric-card:nth-child(3) { animation-delay: 0.3s; }
        .metric-card:nth-child(4) { animation-delay: 0.4s; }
    </style>
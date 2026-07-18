<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Chat - Research AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/premium-theme.css" rel="stylesheet">
    <link href="/assets/css/workspace-theme.css" rel="stylesheet">
    <style>
        /* Chat Layout */
        .chat-wrapper {
            margin-left: var(--sidebar-width);
            display: flex; flex-direction: column;
            height: 100vh;
        }

        /* Top Bar */
        .chat-topbar {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
            padding: 12px 24px;
            display: flex; justify-content: space-between; align-items: center;
            z-index: 100;
        }

        .topbar-left { display: flex; align-items: center; gap: 15px; }
        .topbar-left a { color: var(--text-muted); text-decoration: none; transition: color 0.3s; font-size: 0.95rem; }
        .topbar-left a:hover { color: white; }

        .engine-status {
            display: flex; align-items: center; gap: 8px;
            background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2);
            padding: 6px 14px; border-radius: 20px;
            color: #34d399; font-size: 0.8rem; font-weight: 600;
        }
        .status-dot { width: 8px; height: 8px; border-radius: 50%; background: #34d399; animation: pulse 2s infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }

        /* Chat Container */
        .chat-container {
            flex: 1;
            overflow-y: auto;
            padding: 30px;
            display: flex; flex-direction: column;
            gap: 20px;
        }

        .chat-container::-webkit-scrollbar { width: 6px; }
        .chat-container::-webkit-scrollbar-track { background: transparent; }
        .chat-container::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 3px; }

        /* Message Bubbles */
        .message { display: flex; gap: 12px; max-width: 80%; animation: msgIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .message.user { margin-left: auto; flex-direction: row-reverse; }

        @keyframes msgIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }

        .msg-avatar {
            width: 36px; height: 36px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem; flex-shrink: 0;
        }

        .msg-avatar.ai {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            box-shadow: 0 4px 15px rgba(99,102,241,0.4);
            border: 2px solid rgba(255,255,255,0.1);
        }
        .msg-avatar.human {
            background: linear-gradient(135deg, #1e293b, #0f172a);
            border: 1px solid var(--glass-border);
            color: #94a3b8;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        .msg-content {
            padding: 16px 20px;
            border-radius: 18px;
            line-height: 1.6;
            font-size: 0.95rem;
            letter-spacing: 0.2px;
        }

        .msg-content.ai {
            background: rgba(17, 24, 39, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-top-left-radius: 4px;
            color: var(--text-main);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
        }

        .msg-content.user {
            background: linear-gradient(135deg, var(--primary), #818cf8);
            border-top-right-radius: 4px;
            color: white;
            box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.4);
        }

        .msg-label {
            font-size: 0.75rem; font-weight: 600;
            color: var(--text-muted); margin-bottom: 6px;
            text-transform: uppercase; letter-spacing: 0.5px;
        }

        /* Typing Indicator */
        .typing-indicator {
            display: none;
            padding: 16px 20px;
            background: var(--bg-panel);
            border: 1px solid var(--glass-border);
            border-radius: 18px;
            border-top-left-radius: 4px;
            max-width: 120px;
        }

        .typing-dots { display: flex; gap: 5px; align-items: center; }
        .typing-dots span {
            width: 8px; height: 8px; border-radius: 50%;
            background: var(--text-muted);
            animation: typingBounce 1.4s infinite ease-in-out;
        }
        .typing-dots span:nth-child(2) { animation-delay: 0.2s; }
        .typing-dots span:nth-child(3) { animation-delay: 0.4s; }

        @keyframes typingBounce {
            0%, 80%, 100% { transform: scale(0.7); opacity: 0.4; }
            40% { transform: scale(1); opacity: 1; }
        }

        /* Input Area */
        .chat-input-area {
            background: linear-gradient(to top, rgba(3, 7, 18, 1) 40%, rgba(3, 7, 18, 0));
            padding: 30px 30px 40px 30px;
            border-top: none;
            position: relative;
            z-index: 10;
        }

        .input-wrapper {
            display: flex; gap: 12px; align-items: center;
            max-width: 900px; margin: 0 auto;
        }

        .chat-input {
            flex: 1;
            background: transparent;
            border: none;
            color: var(--text-main);
            padding: 14px 16px;
            font-size: 1rem;
            font-family: 'Outfit', sans-serif;
            transition: all 0.3s;
            outline: none;
            min-width: 0;
        }
        .chat-input::placeholder { color: rgba(148, 163, 184, 0.6); }

        .input-wrapper:focus-within {
            border-color: rgba(99, 102, 241, 0.5);
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.15), 0 5px 15px rgba(0,0,0,0.4);
        }

        .send-btn {
            width: 44px; height: 44px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none; border-radius: 12px;
            color: white; font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 4px 15px rgba(99,102,241,0.4);
            display: flex; align-items: center; justify-content: center;
        }
        .send-btn:hover { transform: scale(1.08); box-shadow: 0 8px 25px rgba(99,102,241,0.6); }
        .send-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; box-shadow: none; }

        /* Custom Dropdown */
        .custom-dropdown {
            position: relative;
            font-family: 'Outfit', sans-serif;
            user-select: none;
        }
        .dropdown-selected {
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.3);
            color: #818cf8;
            font-weight: 600;
            border-radius: 12px;
            cursor: pointer;
            padding: 8px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s;
            font-size: 0.875rem;
            min-width: 170px;
        }
        .dropdown-selected:hover {
            background: rgba(99, 102, 241, 0.2);
            border-color: rgba(99, 102, 241, 0.5);
        }
        .dropdown-options {
            position: absolute;
            bottom: calc(100% + 10px);
            right: 0;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            width: 100%;
            min-width: 190px;
            overflow: hidden;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 10px 30px rgba(0,0,0,0.6), 0 0 0 1px rgba(99,102,241,0.1);
            z-index: 100;
        }
        .custom-dropdown.open .dropdown-options {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .dropdown-option {
            padding: 12px 16px;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 500;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .dropdown-option i {
            font-size: 1rem;
            opacity: 0.7;
            width: 20px;
            text-align: center;
        }
        .dropdown-option:hover {
            background: rgba(255,255,255,0.05);
            color: white;
        }
        .dropdown-option.selected {
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.15), transparent);
            color: #818cf8;
            font-weight: 600;
            border-left: 2px solid var(--primary);
        }
        .dropdown-option.selected i {
            opacity: 1;
            color: #818cf8;
        }
    </style>
	<link rel="icon" href="/assets/img/logo.png" type="image/png">
</head>
<body>
    <!-- Sidebar -->
    <?php include __DIR__ . '/../student/components/sidebar.php'; ?>

    <!-- Chat Interface -->
    <div class="chat-wrapper">
        <!-- Top Bar -->
        <div class="chat-topbar">
            <div class="topbar-left">
                <span class="fw-bold" style="display: flex; align-items: center; gap: 8px; font-size: 1.1rem;">
                    <div style="width: 24px; height: 24px; border-radius: 6px; overflow: hidden;"><img src="/assets/img/logo.png" style="width:100%;height:100%;object-fit:cover;"></div>
                    Research AI Chat
                </span>
            </div>
            <div class="engine-status">
                <div class="status-dot"></div>
                Engine Online
            </div>
        </div>

        <!-- Chat Messages -->
        <div class="chat-container" id="chatContainer">
            <div class="message">
                <div class="msg-avatar ai" style="overflow:hidden; padding: 0;"><img src="/assets/img/logo.png" style="width:100%;height:100%;object-fit:cover;"></div>
                <div>
                    <div class="msg-label"><i class="fa-solid fa-robot"></i> Research AI</div>
                    <div class="msg-content ai">
                        Hello! I'm your Research AI assistant. I can help you formulate research topics, analyze literature, write thesis chapters, and process your data. What would you like to work on today?
                    </div>
                </div>
            </div>
            <div class="message" id="typingIndicator" style="display: none;">
                <div class="msg-avatar ai" style="overflow:hidden; padding: 0;"><img src="/assets/img/logo.png" style="width:100%;height:100%;object-fit:cover;"></div>
                <div>
                    <div class="msg-label"><i class="fa-solid fa-robot"></i> Research AI</div>
                    <div class="msg-content ai" style="padding: 18px 24px;">
                        <div class="typing-dots">
                            <span></span><span></span><span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="chat-input-area">
            <div class="input-wrapper" style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--glass-border); border-radius: 16px; padding: 6px; display: flex; align-items: center; gap: 8px; max-width: 900px; margin: 0 auto; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
                <form id="chatForm" style="flex: 1; display: flex; align-items: center; gap: 8px; margin: 0; width: 100%;">
                    <input type="file" id="fileUpload" accept=".pdf,.csv,.txt" style="display: none;">
                    <button type="button" class="btn btn-link text-white text-decoration-none px-3" onclick="document.getElementById('fileUpload').click()" title="Attach Document (PDF, CSV, TXT)" style="opacity: 0.9; transition: opacity 0.3s; flex-shrink: 0; font-size: 0.95rem; background: rgba(99,102,241,0.2); border-radius: 12px; border: 1px solid rgba(99,102,241,0.3); display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-paperclip"></i> Upload Doc
                    </button>
                    
                    <div id="filePreview" style="display: none; align-items: center; background: rgba(99,102,241,0.2); padding: 4px 10px; border-radius: 12px; font-size: 0.8rem; border: 1px solid rgba(99,102,241,0.3); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 150px;">
                        <i class="fa-solid fa-file-pdf me-2 text-primary"></i> <span id="fileName"></span>
                        <i class="fa-solid fa-times ms-2 text-muted" style="cursor:pointer;" onclick="clearFile()"></i>
                    </div>

                    <input type="text" id="chatInput" class="chat-input" placeholder="Ask me anything about your research..." required autocomplete="off" style="flex: 1; background: transparent; border: none; box-shadow: none; color: white; padding: 10px 10px; outline: none; min-width: 0;">
                    
                    <div class="agent-selector-pill" style="display: flex; align-items: center; flex-shrink: 0;">
                            <div class="dropdown-selected" onclick="toggleDropdown(event)">
                                <span id="selectedAgentText"><i class="fa-solid fa-microscope me-2"></i> Research AI 1.0</span>
                                <i class="fa-solid fa-chevron-down ms-2" style="font-size: 0.75rem;"></i>
                            </div>
                            <div class="dropdown-options" id="dropdownOptions">
                                <div class="dropdown-option selected" onclick="selectAgent('Research AI 1.0', 'fa-microscope')">
                                    <i class="fa-solid fa-microscope"></i> Research AI 1.0 (Gemini)
                                </div>
                                <div class="dropdown-option" onclick="selectAgent('Llama 3.3 70B', 'fa-bolt')">
                                    <i class="fa-solid fa-bolt"></i> Llama 3.3 70B
                                </div>
                            </div>
                            <input type="hidden" id="agentSelect" name="agent" value="Research AI 1.0">
                        </div>
                    </div>

                    <button type="submit" class="send-btn" id="sendBtn" style="border-radius: 12px; height: 42px; width: 42px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="/assets/js/chat.js"></script>
    <script>
        // Custom Dropdown Logic
        const dropdown = document.getElementById('agentDropdown');
        const selectedText = document.getElementById('selectedAgentText');
        const agentInput = document.getElementById('agentSelect');

        function toggleDropdown(event) {
            dropdown.classList.toggle('open');
            event.stopPropagation();
        }

        function selectAgent(name, iconClass) {
            // Update hidden input value
            agentInput.value = name;
            
            // Update button text and icon
            selectedText.innerHTML = `<i class="fa-solid ${iconClass} me-2"></i> ${name}`;
            
            // Update selected class
            document.querySelectorAll('.dropdown-option').forEach(opt => {
                opt.classList.remove('selected');
                if(opt.innerText.trim() === name) {
                    opt.classList.add('selected');
                }
            });
            
            dropdown.classList.remove('open');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!dropdown.contains(e.target)) {
                dropdown.classList.remove('open');
            }
        });
    </script>
</body>
</html>

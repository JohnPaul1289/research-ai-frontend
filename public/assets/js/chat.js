document.addEventListener('DOMContentLoaded', () => {
    const chatForm = document.getElementById('chatForm');
    const chatInput = document.getElementById('chatInput');
    const chatContainer = document.getElementById('chatContainer');
    const typingIndicator = document.getElementById('typingIndicator');
    const sendBtn = document.getElementById('sendBtn');
    let currentSessionId = null;

    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const message = chatInput.value.trim();
        const agentSelect = document.getElementById('agentSelect');
        const agent = agentSelect ? agentSelect.value : 'Research Assistant';
        if (!message) return;

        // Prevent chat if document is still uploading
        if (chatInput.disabled) return;

        // Add user message to UI
        appendMessage('user', message);
        chatInput.value = '';
        
        // Disable input and show thinking
        chatInput.disabled = true;
        sendBtn.disabled = true;
        typingIndicator.style.display = 'block';
        chatContainer.scrollTop = chatContainer.scrollHeight;

        try {
            const response = await fetch('/api/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ message, agent, session_id: currentSessionId })
            });

            const data = await response.json();
            
            typingIndicator.style.display = 'none';

            if (data.session_id) {
                currentSessionId = data.session_id;
            }

            if (data.reply) {
                appendMessage('ai', data.reply, data.agent);
            } else if (data.error) {
                appendMessage('ai', `Error: ${data.error}`);
            } else {
                appendMessage('ai', 'Error: Unexpected response from server.');
            }
        } catch (error) {
            typingIndicator.style.display = 'none';
            appendMessage('ai', 'Error: Could not connect to the Research AI engine.');
            console.error(error);
        } finally {
            chatInput.disabled = false;
            sendBtn.disabled = false;
            chatInput.focus();
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    });

    function appendMessage(sender, text, agentName = 'Research AI') {
        const isUser = sender === 'user';
        const div = document.createElement('div');
        div.className = `message ${isUser ? 'user' : ''}`;
        
        let avatarHTML = isUser ? `<div class="msg-avatar human"><i class="fa-solid fa-user text-white"></i></div>` : `<div class="msg-avatar ai" style="overflow:hidden; padding: 0;"><img src="/assets/img/logo.png" style="width:100%;height:100%;object-fit:cover;"></div>`;
        let labelHTML = isUser ? `<div class="msg-label" style="text-align: right;"><i class="fa-solid fa-user"></i> You</div>` : `<div class="msg-label"><i class="fa-solid fa-robot"></i> ${agentName}</div>`;
        
        // Basic markdown line breaks formatting
        const formattedText = text.replace(/\n/g, '<br>');
        
        div.innerHTML = `
            ${avatarHTML}
            <div style="flex: 1; max-width: 100%;">
                ${labelHTML}
                <div class="msg-content ${isUser ? 'user' : 'ai'}">
                    ${formattedText}
                </div>
            </div>
        `;
        
        // Insert before typing indicator
        chatContainer.insertBefore(div, typingIndicator);
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
    // File Upload Logic
    const fileUpload = document.getElementById("fileUpload");
    const filePreview = document.getElementById("filePreview");
    const fileName = document.getElementById("fileName");

    window.clearFile = function() {
        if(fileUpload) fileUpload.value = "";
        if(filePreview) filePreview.style.display = "none";
    };

    if (fileUpload) {
        fileUpload.addEventListener("change", async function() {
            if (!this.files || this.files.length === 0) return;
            const file = this.files[0];
            
            fileName.textContent = file.name;
            filePreview.style.display = "flex";

            // Auto-upload the document for RAG parsing
            appendMessage("ai", `Parsing document **${file.name}**... Please wait.`);
            chatInput.disabled = true;
            sendBtn.disabled = true;

            const formData = new FormData();
            formData.append("document", file);

            try {
                const response = await fetch("/api/upload-document", {
                    method: "POST",
                    body: formData
                });
                const data = await response.json();
                
                if (data.status === "success") {
                    appendMessage("ai", `? **${file.name}** has been fully read and memorized! You can now ask me questions about it.`);
                } else {
                    appendMessage("ai", `? Failed to read document: ${data.error}`);
                    clearFile();
                }
            } catch (err) {
                appendMessage("ai", "? Network error while uploading document.");
                clearFile();
            } finally {
                chatInput.disabled = false;
                sendBtn.disabled = false;
                chatInput.focus();
            }
        });
    }
});

// Apply theme instantly
(function() {
    var theme = localStorage.getItem('theme');
    if (theme) {
        if (theme === 'dark') {
            document.documentElement.classList.add('pastel-mode');
        } else if (theme !== 'light') {
            document.documentElement.classList.add(theme);
        }
    }
})();

// Leet Speak Converter
function toLeetSpeak(text) {
    const leetMap = {
        'a': '4', 'e': '3', 'g': '6', 'i': '1', 'o': '0', 's': '5', 't': '7',
        'A': '4', 'E': '3', 'G': '6', 'I': '1', 'O': '0', 'S': '5', 'T': '7'
    };
    return text.split('').map(char => leetMap[char] || char).join('');
}

// Apply visual settings based on body data attributes
function applyClientFeatures() {
    const body = document.body;

    // Hand-Drawn Mode
    if (body.dataset.handDrawnMode === 'true') {
        document.documentElement.classList.add('hand-drawn-mode');
    }

    // Leet Speak
    if (body.dataset.leetSpeak === 'true') {
        document.querySelectorAll('[data-lang]').forEach(el => {
            el.textContent = toLeetSpeak(el.textContent);
        });
    }

    // Sarcastic Comments
    if (body.dataset.sarcasticComments === 'true') {
        const overdueTasks = document.querySelectorAll('.task-due-date.overdue');
        if (overdueTasks.length > 0) {
            const sarcasticMessages = [
                "Wow, look at all those overdue tasks. You're a real go-getter.",
                "Don't worry, those tasks will eventually do themselves. Or not.",
                "I'm not saying you're a procrastinator, but I'm not not saying it either.",
                "Are you trying to set a new record for overdue tasks?",
                "If you keep it up, the world might end before you achieve what you set out to do. Takes a lot of pressure, huh?"
            ];
            const randomIndex = Math.floor(Math.random() * sarcasticMessages.length);
            const message = sarcasticMessages[randomIndex];
            const messageElement = document.createElement('p');
            messageElement.textContent = message;
            messageElement.style.textAlign = 'center';
            messageElement.style.color = 'var(--accent)';
            messageElement.style.fontWeight = 'bold';
            document.querySelector('main').prepend(messageElement);
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    
    // --- LANGUAGE SWITCHER ---
    const languageSelect = document.getElementById('language-select');
    
    function updateLanguage(lang) {
        const elements = document.querySelectorAll('[data-lang]');
        elements.forEach(el => {
            const key = el.getAttribute('data-lang');
            if (languages[lang] && languages[lang][key]) {
                el.textContent = languages[lang][key];
            }
        });
        localStorage.setItem('language', lang);
        if (languageSelect) {
            languageSelect.value = lang;
        }
        // Re-apply leet speak after language change
        if (document.body.dataset.leetSpeak === 'true') {
            elements.forEach(el => {
                el.textContent = toLeetSpeak(el.textContent);
            });
        }
    }

    if (languageSelect) {
        languageSelect.addEventListener('change', (e) => {
            updateLanguage(e.target.value);
        });
    }

    // Set initial language from storage
    const savedLanguage = localStorage.getItem('language') || 'en';
    updateLanguage(savedLanguage);

    // Apply features like Leet Speak, Hand-Drawn mode etc.
    applyClientFeatures();

    // --- TASK TOGGLING ---
    const taskItems = document.querySelectorAll('li[data-id]');
    taskItems.forEach(item => {
        const toggleButton = item.querySelector('.toggleBtn');
        if (toggleButton) {
            toggleButton.addEventListener('click', async (e) => {
                e.preventDefault();
                const taskId = item.dataset.id;
                if (!taskId) return;

                const formData = new FormData();
                formData.append('id', taskId);
                
                const csrfToken = toggleButton.dataset.csrf;
                if (csrfToken) {
                    formData.append('csrf', csrfToken);
                }

                try {
                    const response = await fetch('?page=task_toggle', {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        if (data.status === 'done') {
                            item.classList.add('done');
                            toggleButton.textContent = '✅';
                            const title = item.querySelector('.title');
                            if (title) {
                                title.style.textDecoration = 'line-through';
                                title.style.color = 'var(--text-muted)';
                            }
                        } else {
                            item.classList.remove('done');
                            toggleButton.textContent = '⬜';
                            const title = item.querySelector('.title');
                            if (title) {
                                title.style.textDecoration = 'none';
                                title.style.color = 'var(--text)';
                            }
                        }
                    } else {
                        console.error('Server error toggling task.');
                    }
                } catch (error) {
                    console.error('Network error:', error);
                }
            });
        }
    });

    // --- HABIT CHECKING ---
    const habitButtons = document.querySelectorAll('.habit-check-btn');
    habitButtons.forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const habitId = btn.dataset.id;
            if (!habitId) return;

            const formData = new FormData();
            formData.append('id', habitId);
            
            const csrfToken = btn.dataset.csrf;
            if (csrfToken) {
                formData.append('csrf', csrfToken);
            }
            
            const today = new Date().toLocaleDateString('en-CA');
            formData.append('date', today);

            try {
                const response = await fetch('?page=habit_check', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        btn.textContent = 'Checked!';
                        btn.style.background = 'var(--primary)';
                        btn.style.color = 'white';
                        btn.disabled = true;
                        
                        const streakEl = btn.closest('.card').querySelector('.streak strong');
                        if (streakEl) {
                            streakEl.textContent = parseInt(streakEl.textContent) + 1;
                        }
                    }
                }
            } catch (error) {
                console.error('Failed to log habit:', error);
            }
        });
    });

    // --- THEME SWITCHER ---
    const themeSwitcher = document.getElementById('theme-select');
    if (themeSwitcher) {
        var theme = localStorage.getItem('theme') || 'light';
        themeSwitcher.value = theme;

        themeSwitcher.addEventListener('change', (e) => {
            document.documentElement.classList.remove('pastel-mode', 'blue-theme', 'green-theme', 'purple-theme', 'pink-theme', 'orange-theme');
            const selectedTheme = e.target.value;
            if (selectedTheme === 'dark') {
                document.documentElement.classList.add('pastel-mode');
            } else if (selectedTheme !== 'light') {
                document.documentElement.classList.add(selectedTheme);
            }
            localStorage.setItem('theme', selectedTheme);
        });
    }

    // --- SKILLS FILTERING ---
    const categorySelect = document.getElementById('skill-category-select');
    const searchInput = document.getElementById('skill-search-input');
    const skillCards = document.querySelectorAll('.skill-card-item');

    function filterSkills() {
        if (!categorySelect || !searchInput) return;
        
        const category = categorySelect.value.toLowerCase();
        const search = searchInput.value.toLowerCase();

        skillCards.forEach(card => {
            const cardCategory = (card.dataset.category || '').toLowerCase();
            const cardName = (card.dataset.name || '').toLowerCase();
            const matchesCategory = category === '' || cardCategory === category;
            const matchesSearch = search === '' || cardName.includes(search);

            if (matchesCategory && matchesSearch) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    if (categorySelect) categorySelect.addEventListener('change', filterSkills);
    if (searchInput) searchInput.addEventListener('input', filterSkills);
});
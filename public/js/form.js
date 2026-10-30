<<<<<<< Updated upstream
const form = document.querySelector('#cv-form');
const message = document.querySelector('#form-message');

const fieldDefinitions = {
    education: [
        ['school', 'Trường', true],
        ['major', 'Ngành học', true],
        ['period', 'Thời gian', false],
        ['gpa', 'GPA', false]
    ],
    experience: [
        ['company', 'Công ty / tổ chức', true],
        ['position', 'Vị trí', true],
        ['period', 'Thời gian', false],
        ['description', 'Mô tả công việc', false, 'textarea']
    ],
    project: [
        ['name', 'Tên dự án', true],
        ['description', 'Mô tả dự án', false, 'textarea'],
        ['tech_stack', 'Công nghệ (ngăn cách bằng dấu phẩy)', false]
    ],
    certificate: [
        ['name', 'Tên chứng chỉ', true],
        ['organization', 'Tổ chức cấp', false],
        ['year', 'Năm', false]
    ]
};

function addEntry(type) {
    if (type === 'skill') {
        const entry = document.createElement('div');
        entry.className = 'entry skill-entry';
        entry.innerHTML = '<input class="skill" placeholder="Ví dụ: MySQL"><button type="button" class="remove">Xóa</button>';
        document.querySelector('#skills-list').appendChild(entry);
        return;
    }

    const containers = {
        education: 'education-list',
        experience: 'experience-list',
        project: 'projects-list',
        certificate: 'certificates-list'
    };
    const entry = document.createElement('div');
    entry.className = `entry ${type}-entry`;
    entry.innerHTML = fieldDefinitions[type].map(([name, label, required, element = 'input']) => {
        const input = element === 'textarea'
            ? `<textarea class="${name}"></textarea>`
            : `<input class="${name}" ${required ? 'required' : ''}>`;
        return `<label>${label}${required ? ' *' : ''}</label>${input}`;
    }).join('') + '<button type="button" class="remove">Xóa</button>';
    document.getElementById(containers[type]).appendChild(entry);
}

form.addEventListener('click', event => {
    const addButton = event.target.closest('[data-add]');
    if (addButton) {
        addEntry(addButton.dataset.add);
        return;
    }

    const removeButton = event.target.closest('.remove');
    if (removeButton) {
        removeButton.closest('.entry').remove();
    }
});

function getValue(selector) {
    return document.querySelector(selector).value.trim();
}

function readObjectEntries(selector, fields) {
    return [...document.querySelectorAll(`${selector} .entry`)]
        .map(entry => {
            const item = {};
            fields.forEach(field => {
                const input = entry.querySelector(`.${field}`);
                item[field] = field === 'tech_stack'
                    ? input.value.split(',').map(value => value.trim()).filter(Boolean)
                    : input.value.trim();
            });
            return item;
        })
        .filter(item => Object.values(item).some(value => Array.isArray(value) ? value.length > 0 : value !== ''));
}

form.addEventListener('submit', async event => {
    event.preventDefault();
    message.textContent = 'Đang lưu CV...';
    message.style.color = '#303d9e';

    const data = {
        personal: {
            full_name: getValue('#full_name'),
            email: getValue('#email'),
            phone: getValue('#phone'),
            address: getValue('#address')
        },
        objective: getValue('#objective'),
        education: readObjectEntries('#education-list', ['school', 'major', 'period', 'gpa']),
        skills: [...document.querySelectorAll('.skill')]
            .map(input => input.value.trim())
            .filter(Boolean),
        experience: readObjectEntries('#experience-list', ['company', 'position', 'period', 'description']),
        projects: readObjectEntries('#projects-list', ['name', 'description', 'tech_stack']),
        certificates: readObjectEntries('#certificates-list', ['name', 'organization', 'year'])
    };

    try {
        const response = await fetch('../api/save-cv.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                title: getValue('#title'),
                template_id: Number(getValue('#template_id')),
                data
            })
        });
        const result = await response.json();

        if (!response.ok || !result.success) {
            const errors = result.data && result.data.errors ? ` ${result.data.errors.join(' ')}` : '';
            throw new Error(`${result.message || 'Không thể lưu CV.'}${errors}`);
        }

        message.textContent = `${result.message} Mã CV: ${result.data.cv_id}`;
        message.style.color = 'green';
        window.location.href = 'dashboard.php';
    } catch (error) {
        message.textContent = error.message;
        message.style.color = 'crimson';
    }
});
=======
(function () {
    'use strict';

    const form = document.getElementById('cvForm');
    if (!form) return;

    const sections = {
        education: document.getElementById('educationList'),
        skills: document.getElementById('skillsList'),
        experience: document.getElementById('experienceList'),
        projects: document.getElementById('projectsList')
    };

    const templates = {
        education: () => ({ school:'', major:'', period:'', gpa:'' }),
        skills: () => ({ name:'', level:'' }),
        experience: () => ({ company:'', position:'', period:'', description:'' }),
        projects: () => ({ name:'', period:'', role:'', description:'', link:'' })
    };

    let data = window.initialCVData || {
        personal:{full_name:'',job_title:'',email:'',phone:'',address:''},
        objective:'', education:[], skills:[], experience:[], projects:[], languages:[]
    };

    function esc(value) {
        return String(value ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[c]));
    }

    function input(label, key, value, type='text') {
        return `<div class="dynamic-field"><label>${label}</label><input type="${type}" data-key="${key}" value="${esc(value)}"></div>`;
    }

    function textarea(label, key, value) {
        return `<div class="dynamic-field full"><label>${label}</label><textarea data-key="${key}">${esc(value)}</textarea></div>`;
    }

    function renderItem(type, item, index) {
        let html = `<div class="dynamic-item" data-index="${index}">`;
        html += `<div class="dynamic-head"><strong>${type === 'education' ? 'Học vấn' : type === 'skills' ? 'Kỹ năng' : type === 'experience' ? 'Kinh nghiệm' : 'Dự án'} #${index+1}</strong><button type="button" class="remove-item">Xóa</button></div><div class="dynamic-grid">`;
        if (type === 'education') html += input('Trường học','school',item.school) + input('Ngành học','major',item.major) + input('Thời gian','period',item.period) + input('GPA','gpa',item.gpa);
        if (type === 'skills') html += input('Tên kỹ năng','name',item.name) + input('Mức độ','level',item.level);
        if (type === 'experience') html += input('Công ty','company',item.company) + input('Vị trí','position',item.position) + input('Thời gian','period',item.period) + textarea('Mô tả','description',item.description);
        if (type === 'projects') html += input('Tên dự án','name',item.name) + input('Thời gian','period',item.period) + input('Vai trò','role',item.role) + input('Link','link',item.link) + textarea('Mô tả dự án','description',item.description);
        html += '</div></div>';
        return html;
    }

    function render(type) {
        const list = sections[type];
        if (!list) return;
        list.innerHTML = (data[type] || []).map((item,i) => renderItem(type,item,i)).join('');
        if (!data[type] || data[type].length === 0) list.innerHTML = `<div class="empty-item">Chưa có mục nào. Bấm “+ Thêm” để thêm.</div>`;
    }

    Object.keys(sections).forEach(render);

    document.querySelectorAll('[data-add]').forEach(btn => btn.addEventListener('click', function () {
        const type = this.dataset.add;
        data[type].push(templates[type]());
        render(type);
    }));

    Object.keys(sections).forEach(type => sections[type].addEventListener('input', function (e) {
        const field = e.target.closest('[data-key]');
        if (!field) return;
        const item = field.closest('.dynamic-item');
        const index = Number(item.dataset.index);
        data[type][index][field.dataset.key] = field.value;
    }));

    Object.keys(sections).forEach(type => sections[type].addEventListener('click', function (e) {
        if (!e.target.classList.contains('remove-item')) return;
        const item = e.target.closest('.dynamic-item');
        data[type].splice(Number(item.dataset.index),1);
        render(type);
    }));

    function readBasic() {
        const get = name => form.querySelector(`[name="${name}"]`)?.value.trim() || '';
        data.personal = {
            full_name:get('full_name'), job_title:get('job_title'), email:get('email'),
            phone:get('phone'), address:get('address')
        };
        data.objective = get('objective');
        data.languages = get('languages').split(',').map(x=>x.trim()).filter(Boolean);
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        readBasic();
        const button = form.querySelector('button[type="submit"]');
        button.disabled = true; button.textContent = 'Đang lưu...';
        try {
            const payload = {
                id: form.dataset.cvId || null,
                title: form.querySelector('[name="title"]').value.trim(),
                template_id: Number(form.querySelector('[name="template_id"]').value),
                data: data
            };
            const response = await fetch('../api/save-cv.php', {
                method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(payload)
            });
            const result = await response.json();
            if (!response.ok || !result.success) throw new Error(result.message || 'Không thể lưu CV');
            window.location.href = result.redirect;
        } catch (error) {
            alert(error.message);
            button.disabled = false; button.textContent = 'Lưu CV';
        }
    });
})();
>>>>>>> Stashed changes

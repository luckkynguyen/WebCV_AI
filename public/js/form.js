const form = document.querySelector('#cv-form');
const message = document.querySelector('#form-message');
const formConfig = window.cvFormConfig || {
    endpoint: '../api/save-cv.php',
    successRedirect: 'dashboard.php',
    cvId: null,
    initial: null
};

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

function addObjectEntry(type, values = {}) {
    addEntry(type);
    const containerName = type === 'project' ? 'projects' : `${type}s`;
    const entry = document.querySelector(`#${containerName}-list .entry:last-child`);
    Object.entries(values).forEach(([name, value]) => {
        const input = entry.querySelector(`.${name}`);
        if (!input) {
            return;
        }
        input.value = Array.isArray(value) ? value.join(', ') : value;
    });
}

function populateForm(initial) {
    if (!initial || !initial.data) {
        return;
    }

    const data = initial.data;
    const personal = data.personal || {};
    document.querySelector('#title').value = initial.title || 'CV của tôi';
    document.querySelector('#template_id').value = String(initial.template_id || 1);
    document.querySelector('#full_name').value = personal.full_name || '';
    document.querySelector('#email').value = personal.email || '';
    document.querySelector('#phone').value = personal.phone || '';
    document.querySelector('#address').value = personal.address || '';
    document.querySelector('#objective').value = data.objective || '';

    document.querySelector('#skills-list').innerHTML = '';
    (data.skills || []).forEach(skill => {
        const entry = document.createElement('div');
        entry.className = 'entry skill-entry';
        entry.innerHTML = '<input class="skill" placeholder="Ví dụ: PHP"><button type="button" class="remove">Xóa</button>';
        entry.querySelector('.skill').value = skill;
        document.querySelector('#skills-list').appendChild(entry);
    });

    (data.education || []).forEach(item => addObjectEntry('education', item));
    (data.experience || []).forEach(item => addObjectEntry('experience', item));
    (data.projects || []).forEach(item => addObjectEntry('project', item));
    (data.certificates || []).forEach(item => addObjectEntry('certificate', item));
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
        const payload = {
            title: getValue('#title'),
            template_id: Number(getValue('#template_id')),
            data
        };
        if (formConfig.cvId !== null) {
            payload.cv_id = formConfig.cvId;
        }

        const response = await fetch(formConfig.endpoint, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const result = await response.json();

        if (!response.ok || !result.success) {
            const errors = result.data && result.data.errors ? ` ${result.data.errors.join(' ')}` : '';
            throw new Error(`${result.message || 'Không thể lưu CV.'}${errors}`);
        }

        message.textContent = result.message;
        message.style.color = 'green';
        window.location.href = formConfig.successRedirect.replace(
            ':id',
            String(result.data.cv_id)
        );
    } catch (error) {
        message.textContent = error.message;
        message.style.color = 'crimson';
    }
});

populateForm(formConfig.initial);
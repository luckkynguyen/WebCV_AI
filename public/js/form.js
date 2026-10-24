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
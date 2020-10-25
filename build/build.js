/*
 * Users Package
 * Copyright (C) 2020 Dynamic Suite Team
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software Foundation,
 * Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301  USA
 */

const path = require('path');
const fs = require('fs');
const js_compiler = require(path.join(process.argv[2], 'npm/node_modules/uglify-es'));
const sass_compiler = require(path.join(process.argv[2], 'npm/node_modules/sass'));
const dir = path.join(__dirname, '../components');
const newline = require('os').EOL;

process.chdir(dir);

// File JS output
let output_js = (
    `/*${newline}` +
    ` * Dynamic Suite: Users${newline}` +
    ` * License: GPLv3${newline}` +
    ` */${newline}`
);

// File CSS output
let output_css = ''

// Load the component files
let files = fs.readdirSync(dir);

// Iterate on files
for (const file of files) {

    // Read the file
    let data = fs.readFileSync(path.join(dir, file)).toString();

    // No template, invalid component
    if (!data.includes('<template>') || !data.includes('</template>')) {
        console.log(`File ${file} missing template`);
        process.exit(1);
    }

    // Find the template
    let template = data.substring(
        data.lastIndexOf('<template>') + 10,
        data.lastIndexOf('</template>')
    );

    // Build the component
    let name = file.substring(0, file.length - 4).replace(/([A-Z])/g, '-$1').toLowerCase().substr(1)
    let component = `Vue.component("users-${name}", {`

    // Clean the template
    template = template
        .trim()
        .replace(/<\!--.*?-->/g, '') // Remove comments
        .replace(/>\s+</g, '><') // Remove spaces between tags
        .replace(' />', '/>') // Remove more whitespace
        .replace(/(\r\n|\n|\r)/gm, '') // Remove line breaks
        .replace(/\s\s+/g, ' ') // Remove large whitespace chunks
        .replace(/>\s+/g, '>') // Remove whitespace after tag ends
        .replace(/\s>+/g, '>') // Remove whitespace before tag ends
        .replace(/\s<+/g, '<') // Remove whitespace after tag starts
        .replace(/<\s+/g, '<'); // Remove whitespace before tag starts

    // Update the component
    component += 'template: `' + template + '`'

    // No template, invalid component
    if (data.includes('<script>') && data.includes('</script>')) {

        // Find the script
        let script = data.substring(
            data.lastIndexOf('<script>') + 8,
            data.lastIndexOf('</script>')
        );

        // Clean the script
        script = script
            .trim()
            .replace('export default {', ''); // Remove export

        let pos = script.lastIndexOf('}');
        script = script.substring(0, pos) + script.substring(pos  + 1)
        script = script.trim();

        component += `,${script}`;

    }

    // Complete component
    component += '});';

    // Build JS
    let ugly_js = js_compiler.minify(component);
    if (typeof ugly_js.error !== 'undefined') {
        console.log(ugly_js.error);
        process.exit(1);
    } else {
        output_js += `${ugly_js.code}${newline}`;
    }

    // Build CSS
    if (data.includes('<style lang="sass">') && data.includes('</style>')) {
        let sass = data.substring(
            data.lastIndexOf('<style lang="sass">') + 19,
            data.lastIndexOf('</style>')
        );
        output_css += sass;
    }

}

// Add view container output
output_js += 'if(document.getElementById("users-component-view")){new Vue({el:"#users-component-view"});};';

// Minify
let ugly_css = sass_compiler.renderSync({
    data: output_css,
    indentedSyntax: true,
    outputStyle: 'compressed'
});
output_css = (
    `/*${newline}` +
    ` * Dynamic Suite: Users${newline}` +
    ` * License: GPLv3${newline}` +
    ` */${newline}` +
    ugly_css.css.toString()
);

// Write JS
fs.writeFileSync(path.normalize('../client/js/users.min.js'), output_js);

// Write CSS
fs.writeFileSync(path.normalize('../client/css/users.min.css'), output_css);
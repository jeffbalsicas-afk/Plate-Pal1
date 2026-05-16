import puppeteer from 'puppeteer';
import { fileURLToPath } from 'url';
import { dirname, join } from 'path';

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

async function exportToPDF() {
    console.log('🚀 Starting PDF export...');
    
    const browser = await puppeteer.launch({
        headless: 'new',
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    });
    
    try {
        const page = await browser.newPage();
        
        // Set viewport to A4 dimensions
        await page.setViewport({
            width: 794,  // A4 width in pixels at 96 DPI
            height: 1123, // A4 height in pixels at 96 DPI
            deviceScaleFactor: 2 // Higher quality
        });
        
        // Export Infographic
        const infographicHtmlPath = join(__dirname, 'deliverables', 'platepal-infographic-a4.html');
        const infographicOutputPath = join(__dirname, 'deliverables', 'platepal-infographic-a4.pdf');
        
        console.log('📄 Loading Infographic HTML file...');
        await page.goto(`file:///${infographicHtmlPath.replace(/\\/g, '/')}`, {
            waitUntil: 'networkidle0'
        });
        
        console.log('🖨️  Generating Infographic PDF...');
        await page.pdf({
            path: infographicOutputPath,
            format: 'A4',
            printBackground: true,
            margin: {
                top: 0,
                right: 0,
                bottom: 0,
                left: 0
            },
            preferCSSPageSize: true
        });
        
        console.log('✅ Infographic PDF exported successfully!');
        console.log(`📁 Location: ${infographicOutputPath}`);

        // Export Pamphlet
        const pamphletHtmlPath = join(__dirname, 'deliverables', 'platepal-pamphlet-a4.html');
        const pamphletOutputPath = join(__dirname, 'deliverables', 'platepal-pamphlet-a4.pdf');
        
        console.log('📄 Loading Pamphlet HTML file...');
        await page.goto(`file:///${pamphletHtmlPath.replace(/\\/g, '/')}`, {
            waitUntil: 'networkidle0'
        });
        
        console.log('🖨️  Generating Pamphlet PDF...');
        await page.pdf({
            path: pamphletOutputPath,
            format: 'A4',
            printBackground: true,
            margin: {
                top: 0,
                right: 0,
                bottom: 0,
                left: 0
            },
            preferCSSPageSize: true
        });
        
        console.log('✅ Pamphlet PDF exported successfully!');
        console.log(`📁 Location: ${pamphletOutputPath}`);
        console.log('🎉 All PDFs exported successfully!');
        
    } catch (error) {
        console.error('❌ Error exporting PDF:', error.message);
        throw error;
    } finally {
        await browser.close();
    }
}

exportToPDF().catch(console.error);

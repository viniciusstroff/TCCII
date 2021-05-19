import ReportList from './Reports/ReportList';
import ReportAdd from './Reports/ReportAdd';
import Example from './components/ExampleComponent';
import ReportPendingList from './ReportsPending/ReportPendingList';

const routes = [
    {
        path: '/audits',
        name: 'home',
        component: Example
    },
    {
        path: '/reports',
        name: 'reports-list',
        component: ReportList
    },
    {
        path: '/report',
        name: 'report-add',
        component: ReportAdd
    },
    {
        path: '/report/:id',
        name: 'report-edit',
        component: ReportAdd
    },
    {
        path: '/report-pending',
        name: 'report pending list',
        component: ReportPendingList
    }
];

export default routes
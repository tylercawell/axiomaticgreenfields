import { User } from "./auth"

export interface Company {

    id: number
    name: string
    
}

export interface Branch {

    id: number
    company_id: number
    name: string

}

export interface Employee {

    id: number
    company_id: number
    branch_id: number
    first_name: string
    last_name: string
    employee_number: string
    email: string
    full_name: string
}

export interface CommissionNote {
    employee: Employee
    note: string
    author: User
    id: number
    employee_id: number
    company_id: number
    branch_id: number
    author_id: number
    amount: number
    description: string
    created_at: string
    updated_at: string

}

export interface CommissionNotesPageProps{

    companies: Company[]
    branches: Branch[]
    employees: Employee[]
    commissionNotes: CommissionNote[]

    filters: {
        company_id: number | null
        branch_id: number | null
        employee_id: number | null
    }
    can: {
        view: boolean
        manage: boolean
    }

}